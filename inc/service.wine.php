<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('init.php');
require_once('class.wine.php');
require_once('tcpdf/tcpdf.php');
?>
<?
if(isset($_POST['function']) && !is_null($_POST['function'])) {
	
	$function = $_POST['function'];
	
} else {
	die('Sorry. Error:' . $_POST['function']);
}

switch ($function) {

	case 'addWine': addWine($_POST, $dbh);break;
	case 'updateWine': updateWine($_POST,$dbh);break;
	//case 'removeWine': removeWine($_POST);break;
	case 'addInventory': addInventory($_POST,$dbh);break;
	case 'removeInventory': removeInventory($_POST,$dbh);break;
	case 'transferInventory': transferInventory($_POST,$dbh);break;
	case 'findWineByUPC': findWineByUPC($_POST,$dbh);break;
	case 'summaryByUPC': summaryByUPC($_POST,$dbh);break;
	case 'completeInventory': completeInventory($_POST,$dbh);break;
	case 'exportPDF': exportPDF($_POST,$dbh);break;
	case 'exportCSV': exportCSV($_POST,$dbh);break;
	
}

function addWine($arr, $dbh) {
	$wine = new Wine($dbh);
	if(	$arr['upc']!='' && $arr['name']!='' && $arr['sell_price']!='' && $arr['cost']!='' && $arr['type']!='') {
		if(!$wine->fetchWineByUPC($arr['upc'])) {
				
				if($arr['sell_price'] <= 0 || $arr['cost'] <= 0) {
					die('Cost and Selling Price must be greater than zero');
				}
				$wine->setUPC($arr['upc']);
				$wine->setName($arr['name']);
				$wine->setType($arr['type']);
				$wine->setVintage($arr['vintage']);
				$wine->setSellPrice($arr['sell_price']);
				$wine->setCost($arr['cost']);
			
				
				$id = $wine->writeWineToTable();
				
				if($id != 0) {
					$args = array(
						'quantity'=> $arr['initial_inventory'],
						'cost'=>$arr['cost'],
						'notes'=>'Initial Inventory',
						'location'=>$arr['location']
					);
					$id = $wine->addInventory($args);
					if($id != 0) {
						die('Wine Added');
					} else {
						die('Wine Added, but initial inventory creation failed.  Please add the initial inventory manually.');
					}
				} else {
					die('Database Error.');
				}
			} else {
				die('A wine with that UPC already exists in your cellar.');
			}
		} else {
			die('You must set all parameters to create a wine.');
		}
}

function updateWine($arr,$dbh) {
	$wine = new Wine($dbh);
	if(	!is_null($arr['upc']) && !is_null($arr['name']) &&
		!is_null($arr['vintage']) && !is_null($arr['sell_price']) &&
		!is_null($arr['wine_id']) && !is_null($arr['type'])) {
		
			$wine->setID($arr['wine_id']);
			$wine->setUPC($arr['upc']);
			$wine->setType($arr['type']);
			$wine->setName($arr['name']);
			$wine->setVintage($arr['vintage']);
			$wine->setCost($arr['cost']);
			$wine->setSellPrice($arr['sell_price']);
			
			$id = $wine->writeWineToTable();
			if($id != 0) {
				die('Wine Updated.');
			} else {
				die('Database Error.');
			}
		} else {
			die('You must set all parameters to update a wine.');
		}
}
/*
function removeWine($arr) {

}*/
function addInventory($arr, $dbh) {
	$wine = new Wine($dbh);
	if(!is_null($arr['wine_id']) && $arr['wine_id'] != '') {
		$wine->fetchWineById($arr['wine_id']);
	} else {
		die('Wine Not Found');
	}
	if(!is_null($arr['wine_id']) && !is_null($arr['quantity']) && !is_null($arr['cost'])) {
		$args = array(
			'quantity'=> $arr['quantity'],
			'cost'=>$arr['cost'],
			'notes'=>'Initial Inventory',
			'location'=>$arr['location']
		);
		$id = $wine->addInventory($args);
		if($id != 0) {
			die('Inventory Added');
		} else {
			die('Database Error');
		}
	}	
}

function removeInventory($arr,$dbh) {
	$wine = new Wine($dbh);
	if(!is_null($arr['wine_id']) && $arr['wine_id'] != '') {
		$wine->fetchWineById($arr['wine_id']);
	} else {
		die('Wine Not Found');
	}
	if(!is_null($arr['wine_id']) && !is_null($arr['quantity']) && !is_null($arr['sell_price'])) {
		$args = array('location'=>$arr['location']);
		if($arr['quantity'] <= $wine->getCurrentInventory($args)) {
			$args = array(
				'quantity'=>$arr['quantity'],
				'sell_price'=>$arr['sell_price'],
				'notes'=>$arr['notes'],
				'location'=>$arr['location']
			);
			$id = $wine->removeInventory($args);
			if($id != 0) {
				die('Inventory Removed');
			} else {
				die('Database Error');
			}
		} else {
			die('You cannot remove more inventory than you have in stock.  Current inventory for this item in this location is ' . $wine->getCurrentInventory($args) . '.');
		}
	}
}
function transferInventory($arr,$dbh) {
	$wine = new Wine($dbh);
	if(!is_null($arr['wine_id']) && $arr['wine_id'] != '') {
		$wine->fetchWineById($arr['wine_id']);
	} else {
		die('Wine Not Found');
	}
	if(!is_null($arr['wine_id']) && !is_null($arr['quantity'])) {
		$args = array('location'=>$arr['location']);
		if($arr['quantity'] <= $wine->getCurrentInventory($args)) {
			$args = array(
				'quantity'=>$arr['quantity'],
				'sell_price'=>0,
				'notes'=>'Inventory Transfer To ' . getLocationName($arr['location-to'], $dbh) . ' :::: ' . $arr['notes'],
				'location'=>$arr['location']
			);
			$fromid = $wine->removeInventory($args);
			$args = array(
				'quantity'=>$arr['quantity'],
				'cost'=>0,
				'notes'=> 'Inventory Transfer From ' . getLocationName($arr['location'],$dbh) . ' :::: ' . $arr['notes'],
				'location'=>$arr['location-to']
			);
			
			$toid = $wine->addInventory($args);
			if($fromid != 0 && $toid != 0) {
				die('Inventory Transferred');
			} else {
				die('Database Error');
			}
		} else {
			die('You cannot remove more inventory than you have in stock.  Current inventory for this item in this location is ' . $wine->getCurrentInventory($args) . '.');
		}
	}
}

function getLocationName($arr,$dbh) {

	$res = $dbh->query("SELECT locname FROM locations WHERE id='" . $arr . "'");
	$name = $res->fetch(PDO::FETCH_ASSOC);
	return $name['locname'];
	
}
function findWineByUPC($arr, $dbh) {
	$wine = new Wine($dbh);
	$wine->fetchWineByUPC($arr['upc']);
	$json = $wine->jsonEncode($arr['location']);
	
	die($json);
		
}

function summaryByUPC($arr, $dbh) {
	
	$wine = new Wine($dbh);
	$wine->fetchWineByUPC($arr['upc']);
	
	if(isset($arr['to']) && $arr['to'] != null && $arr['to'] != '') {
		$to = $arr['to'];
	} else {
		$to = null;
	}
	
	if(isset($arr['from']) && $arr['from'] != null && $arr['from'] != '') {
		$from = $arr['from'];
	} else {
		$from = null;
	}
	
	$json = $wine->getInventorySummary($from, $to);
	//Generate HTML and echo it out;
	
	$data = json_decode($json, true);
	if(!isset($data[0])) {
		die('No data was found matching your criteria.');
	}
	$args = array('date'=>$from);
	if(is_null($from)) {
		$curInv = 0;
	} else {
		$curInv = $wine->getCurrentInventory($args);
	}
	$runTot = $curInv;
	$HTML = '<h3>Inventory Summary</h3>';
	$HTML .= '<label class="norm">Name:</label><p>' . $data[0]['name'] . '</p><br>';
	$HTML .= '<label class="norm">UPC:</label><p>' . $data[0]['upc'] . '</p><br>';
	$HTML .= '<label class="norm">Beginning Inventory:</label><p>' . $curInv . '</p><br>';
	$HTML .= '';
	$TABLE = '<table>';
	$TABLE .= '<tr><th>Date/Time</th><th>Quantity</th><th>Location</th><th>Cost</th><th>Selling Price</th><th>Total</th></tr>';
	foreach($data as $row) {
		$runTot = $runTot + $row['quantity'];
		$TABLE .= '<tr><td>' . $row['datetime'] . '</td><td>' . $row['quantity'] . '</td><td>' . $row['locname'] . '</td><td>' . $row['cost'] . '</td><td>' . $row['sell_price'] . '</td><td>' . $runTot . '</td></tr>';
	}
	$TABLE .= '</table>';
	$HTML .= '<label class="norm">Ending Inventory:</label><p>' . $runTot . '</p><br>';
	$args = array('date'=>$to);
	$details = $wine->getDetailInventory($args);
	foreach($details AS $key=>$inv) {
		$HTML .= '<div class="location-inventory">' . $key . ': ' . $inv . '</div>';
	}
	$HTML .= $TABLE;
	
	die($HTML);
	
}

function completeInventory($arr, $dbh) {

	$wine = new Wine($dbh);
	
	if(isset($arr['to']) && $arr['to'] != null) {
		$to = $arr['to'];
	} else {
		$to = null;
	}
	
	$wines = $wine->fetchAllWines();
	
	$arr = json_decode($wines,true);
	
	
	$HTML = '<h3>Complete Inventory</h3>';
	$HTML .= '<label style="width:7em;">Criteria:</label><p>' . ($to!=null?'As Of - ' . $to:'As Of - N/A') . '</p>';
	$HTML .= '<div class="wine-inventory-report">';
	foreach($arr AS $a) {
		$curWine = new Wine($dbh);
		$curWine->fetchWineByUPC($a['upc']);
		$args = array('date'=>$to);
		$inv = $curWine->getCurrentInventory($args);
		//cur is json summary of inventory
		$HTML .= '<div class="wine-detail" style="display:block;min-height:50px;background:#eee; border: 2px solid #fff;padding:5px;">';
		$HTML .= '<div class="wine-title" style="font-size: 15px; font-weight:bold;">' . $curWine->getUPC() . ' - ' . $curWine->getName() . '</div>';
		$HTML .= '<div class="wine-info" style="font-size: 12px; margin-top:5px;"><span class="wine-info-detail">Total Inventory: ' . $curWine->getCurrentInventory($args) . '</span><span class="wine-info-detail">Cost/Bottle: ' . number_format($curWine->getCost(),2) . '</span><span class="wine-info-detail">Total Cost: ' . number_format($curWine->getCurrentInventory($args)*$curWine->getCost(), 2) . '</span><span class="wine-info-detail">Selling Price: ' . number_format($curWine->getSellPrice(),2) . '</span></div>';
		$HTML .= '<div class="inventory-detail" style="font-size: 12px; margin-top:5px; margin-left: 20px;">';
		$details = $curWine->getDetailInventory($args);
		foreach($details AS $key=>$inv) {
			$HTML .= '<div class="location-inventory">' . $key . ': ' . $inv . '</div>';
		}
		$HTML .= '</div></div>';
		//$HTML .= '<tr><td>' . $curWine->getUPC() . '</td><td>' . $curWine->getName() . '</td><td>' . $curWine->getCurrentInventory($args) . '</td><td>' . '' . '</td><td>' . number_format($curWine->getCost(),2) . '</td><td>' . number_format($curWine->getCurrentInventory($args)*$curWine->getCost(),2) . '</td><td>' . number_format($curWine->getSellPrice(),2) . '</td><td>' . $curWine->getCost()/$curWine->getSellPrice() . '</td></tr>';
		
	}
	$HTML .= '</div>';
	
	die($HTML);
	
}

function exportPDF($arr, $dbh) {
	if(isset($arr['reportType']) && $arr['reportType'] == 'summaryByUPC') {

		$wine = new Wine($dbh);
		$wine->fetchWineByUPC($arr['upc']);
		
		if(isset($arr['toDate']) && $arr['toDate'] != null && $arr['toDate'] != '') {
			$to = $arr['toDate'];
		} else {
			$to = null;
		}
		
		if(isset($arr['fromDate']) && $arr['fromDate'] != null && $arr['fromDate'] != '') {
			$from = $arr['fromDate'];
		} else {
			$from = null;
		}
		
		$json = $wine->getInventorySummary($from, $to);
		//Generate HTML and echo it out;
		
		$data = json_decode($json, true);
		if(!isset($data[0])) {
			die('No data was found matching your criteria.');
		}
		$args = array('date'=>$from);
		if(is_null($from)) {
			$curInv = 0;
		} else {
			$curInv = $wine->getCurrentInventory($args);
		}
		$runTot = $curInv;
		$HTML =  <<<EOF
<style>
h3 {
	font-size:20px;
	margin-top:20px;
	color: #760101;
	margin-bottom:15px;
}
label {
	display:inline;
}
p {
	display:inline;
	font-size:12px;
	color: #000;
	margin-bottom:15px;
}
td,th {
	border: 1px solid #000;
	padding:2px;
}
th {
	font-size:18px;
	background-color:#ADADAD;
}
</style>
EOF;
		$HTML .= '<h3>Inventory Summary</h3>';
		$HTML .= '<table><tr><td style="border:none;">Name:</td><td style="border:none;">' . $data[0]['name'] . '</td></tr>';
		$HTML .= '<tr><td style="border:none;">UPC:</td><td style="border:none;">' . $data[0]['upc'] . '</td></tr>';
		$HTML .= '<tr><td style="border:none;">Beginning Inventory:</td><td style="border:none;">' . $curInv . '</td></tr>';
		

		$TABLE = '<table>';
		$TABLE .= '<tr><th align="center" valign="middle" width="30%" height="24px"><font size="13px">Date/Time</font></th><th align="center" valign="middle" width="8%"><font size="13px">Qty</font></th><th align="center" valign="middle" width="15%"><font size="13px">Location</font></th><th align="center" valign="middle" width="15%"><font size="13px">Cost</font></th><th align="center" valign="middle" width="13%"><font size="13px">Price</font></th><th align="center" valign="middle"><font size="13px">Total</font></th></tr>';
		foreach($data as $row) {
			$runTot = $runTot + $row['quantity'];
			$TABLE .= '<tr><td align="center" valign="middle" width="30%" height="20px">' . $row['datetime'] . '</td><td align="center" valign="middle" width="8%">' . $row['quantity'] . '</td><td align="center" valign="middle" width="15%">' . $row['locname'] . '</td><td align="center" valign="middle" width="15%">' . $row['cost'] . '</td><td align="center" valign="middle" width="13%">' . $row['sell_price'] . '</td><td align="center" valign="middle">' . $runTot . '</td></tr>';
		}
		$TABLE .= '</table>';
		$HTML .= '<tr><td style="border:none;">Ending Inventory:</td><td style="border:none;">' . $runTot . '</td></tr></table>';
		$details = $wine->getDetailInventory($args);
		$details = $wine->getDetailInventory($args);
		foreach($details AS $key=>$inv) {
			$HTML .= '<div class="location-inventory">' . $key . ': ' . $inv . '</div>';
		}
		$HTML .= '<br><br>';
		$HTML .= $TABLE;
		
		//Create PDF
		
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Highland Country Club');
		$pdf->SetTitle('Inventory Summary');
		$pdf->SetSubject('Inventory Summary');
		$pdf->SetKeywords('PDF, Inventory Summary');

		// set default header data
		$pdf->SetHeaderData('', '', date('Y-m-d'), '');

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$pdf->SetFont('dejavusans','',10);
		$pdf->addPage();
		$pdf->writeHTML($HTML,true,false,true,false,'');
		$pdf->lastPage();
		$pdf->Output('summaryByUPC.pdf','I');
		
		
		//END CREATE PDF
	} else if (isset($arr['reportType']) && $arr['reportType'] == 'completeInventory') {
		$wine = new Wine($dbh);
	
		if(isset($arr['toDate']) && $arr['toDate'] != null) {
			$to = $arr['toDate'];
		} else {
			$to = null;
		}
		
		$wines = $wine->fetchAllWines();
		
		$arr = json_decode($wines,true);
		$HTML =  <<<EOF
<style>
h3 {
	font-size:20px;
	margin-top:20px;
	color: #760101;
	margin-bottom:15px;
}
label {
	display:inline;
}
p {
	display:inline;
	font-size:12px;
	color: #000;
	margin-bottom:15px;
}
td,th {
	border: 1px solid #000;
	padding:2px;
}
th {
	font-size:18px;
	background-color:#ADADAD;
}
</style>
EOF;
		
		$HTML = '<h3>Complete Inventory</h3>';
		$HTML .= '<label style="width:7em;">Criteria:</label><p>' . ($to!=null?'As Of - ' . $to:'As Of - N/A') . '</p>';
		$HTML .= '<div class="wine-inventory-report">';
		foreach($arr AS $a) {
			$curWine = new Wine($dbh);
			$curWine->fetchWineByUPC($a['upc']);
			$args = array('date'=>$to);
			$inv = $curWine->getCurrentInventory($args);
			//cur is json summary of inventory
			$HTML .= '<div class="wine-detail" style="display:block;width:100%;min-height:50px;background:#eee; border: 2px solid #bbb;padding:5px;">';
			$HTML .= '<div class="wine-title" style="font-size: 15px; font-weight:bold;">' . $curWine->getUPC() . ' - ' . $curWine->getName() . '</div>';
			$HTML .= '<div class="wine-info" style="font-size: 12px; margin-top:5px;">Total Inventory: ' . $curWine->getCurrentInventory($args) . ' Cost/Bottle: ' . number_format($curWine->getCost(),2) . ' Total Cost: ' . number_format($curWine->getCurrentInventory($args)*$curWine->getCost(), 2) . ' Selling Price: ' . number_format($curWine->getSellPrice(),2) . '</div>';
			$HTML .= '<div class="inventory-detail" style="font-size: 12px; margin-top:5px; text-indent: 20px;">';
			$details = $curWine->getDetailInventory($args);
			foreach($details AS $key=>$inv) {
				$HTML .= '<div class="location-inventory">' . $key . ': ' . $inv . '</div>';
			}
			$HTML .= '</div></div>';
			//$HTML .= '<tr><td>' . $curWine->getUPC() . '</td><td>' . $curWine->getName() . '</td><td>' . $curWine->getCurrentInventory($args) . '</td><td>' . '' . '</td><td>' . number_format($curWine->getCost(),2) . '</td><td>' . number_format($curWine->getCurrentInventory($args)*$curWine->getCost(),2) . '</td><td>' . number_format($curWine->getSellPrice(),2) . '</td><td>' . $curWine->getCost()/$curWine->getSellPrice() . '</td></tr>';
			
		}
		$HTML .= '</div>';
				//Create PDF
		
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Highlands Country Club');
		$pdf->SetTitle('Complete Inventory');
		$pdf->SetSubject('Complete Inventory');
		$pdf->SetKeywords('PDF,Complete Inventory');

		// set default header data
		$pdf->SetHeaderData('', '', date('Y-m-d'), '');

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		$pdf->SetFont('dejavusans','',10);
		$pdf->addPage();
		$pdf->writeHTML($HTML,true,false,true,false,'');
		$pdf->lastPage();
		$pdf->Output('completeInventory.pdf','I');
		
	} else {
		die('Invalid Report Type');
	}
}

function exportCSV($arr, $dbh) {
	if(isset($arr['reportType']) && $arr['reportType'] == 'summaryByUPC') {
				$wine = new Wine($dbh);
		$wine->fetchWineByUPC($arr['upc']);
		
		if(isset($arr['toDate']) && $arr['toDate'] != null) {
			$to = $arr['toDate'];
		} else {
			$to = null;
		}
		
		if(isset($arr['fromDate']) && $arr['fromDate'] != null) {
			$from = $arr['fromDate'];
		} else {
			$from = null;
		}
		
		$json = $wine->getInventorySummary($from, $to);
		
		//Generate HTML and echo it out;
		
		$data = json_decode($json, true);
		if(!isset($data[0])) {
			die('No data was found matching your criteria.');
		}
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/tmp/summaryByUPC.csv', 'w');
		$header = 'UPC,Name,Cost,SellingPrice,Quantity,DateTime,Location';
		fwrite($fp,$header);
		fwrite($fp,PHP_EOL);
		foreach ($data as $row) {
			fputcsv($fp, $row);
		}
		fclose($fp);
		header('Location: /tmp/summaryByUPC.csv');
	} else if (isset($arr['reportType']) && $arr['reportType'] == 'completeInventory') {
		$wine = new Wine($dbh);
	
		if(isset($arr['toDate']) && $arr['toDate'] != null) {
			$to = $arr['toDate'];
		} else {
			$to = null;
		}
		$args = array('date'=>$to);
		$wines = $wine->fetchAllWines();
		
		$data = json_decode($wines,true);
		$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/tmp/completeInventory.csv', 'w');
		$header = 'UPC,Name,Qty,Cost/Bottle,Total Cost,Selling Price,Cost/Selling Price';
		fwrite($fp,$header);
		fwrite($fp,PHP_EOL);
		foreach ($data as $row) {
			$curWine = new Wine($dbh);
			$curWine->fetchWineByUPC($row['upc']);
			$args = array('date'=>$to);
			$inv = $curWine->getCurrentInventory($args);
			//cur is json summary of inventory
			
			$row = '' . $curWine->getUPC() . ',' . $curWine->getName() . ',' . $curWine->getCurrentInventory($args) . ',' . number_format($curWine->getCost(),2,'.','') . ',' . number_format($curWine->getCurrentInventory($args)*$curWine->getCost(),2,'.','') . ',' . number_format($curWine->getSellPrice(),2,'.','') . ',' . $curWine->getCost()/$curWine->getSellPrice();
			fwrite($fp,$row);
			fwrite($fp, PHP_EOL);
		}
		fclose($fp);
		header('Location: /tmp/completeInventory.csv');
	} else {
		die('Invalid Report Type');
	}
}


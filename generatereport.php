<?php
require_once('/inc/init.php');
if(isset($_POST{'reportType'])) {
	$reportType = $_POST['reportType'];
} else {
	die('You have not set a report type');
}

switch ($reportType) {
	case 'summaryByUPC': summaryByUPC($_POST, $dbh); break;
}


function summaryByUPC($arr, $dbh) {

	//check if UPC exists else die
	if(!isset($_POST['upc'])) {
		die('You must enter a UPC');
	}
	//Build query and execute PDO query
	$query = 	"SELECT wine.upc, wine.name, inventory.cost, inventory.sell_price, inventory.quantity, inventory.datetime " .
				"FROM inventory left join wine on inventory.wine_id = wine.id ";
	$where =	"WHERE wine.upc = :upc";
	
	if(isset($_POST['fromDate']) && $_POST['fromDate'] != ''){
		$from = date('Y-m-d', $_POST['fromDate']);
		$where .= " AND inventory.datetime >= :from";
	}
	if(isset($_POST['toDate']) && $_POST['toDate'] != ''){
		$to = date('Y-m-d', $_POST['toDate']);
		$where .= " AND inventory.datetime >= :to";
	}
	
	$query .= $where;
	
	$data = $dbh->prepare($query);
	$data->bindValue(':upc',$_POST['upc']);
	
	if(isset($_POST['fromDate']) && $_POST['fromDate'] != ''){
		$data->bindValue(':from', $from);
	}
	if(isset($_POST['toDate']) && $_POST['toDate'] != ''){
		$data->bindValue(':to',$to);
	}
	
	$data->execute();
	
	if($data) {
		//Create report HTML
		$row = $data->fetch(PDO::FETCH_ASSOC);
		$html = '<h3>' . $row['upc'] . '</h3>';
		
		
	
	

}

?>
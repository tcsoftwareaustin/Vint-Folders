<?php
include_once('init.php');
include_once('functions.php');

if(isset($_POST['pageId']) && !empty($_POST['pageId'])){
   $id=$_POST['pageId'];
}else{
   $id='0';
}

$pageLimit=PAGE_PER_NO*$id;
$query="select inventory.*, locations.locname from inventory left join wine on inventory.wine_id = wine.id LEFT JOIN locations on inventory.location = locations.id where wine.upc = :upc order by id limit $pageLimit,".PAGE_PER_NO;
$res=$dbh->prepare($query);
$res->bindValue(':upc',$_POST['upc']);
$res->execute();
$count=$res->rowCount();
$HTML='<div id="wine_list"><table style="margin:15px auto;">';
$HTML.='<tr><th>Date/Time</th><th>Quantity</th><th>Cost</th><th>Selling Price</th><th>Location</th><th>Notes</th></tr>';
if($count > 0){
while($row = $res->fetch(PDO::FETCH_ASSOC)){
   $HTML.='<tr onclick="window.location.assign(\'/displayinventory/' . $row['id'] . '/\')">';
   $HTML.='<td>'.$row['datetime'].'</td>';
   $HTML.='<td>'.$row['quantity'].'</td>';
   $HTML.='<td>'.$row['cost'].'</td>';
   $HTML.='<td>'.$row['sell_price'].'</td>';
   $HTML.='<td>'.$row['locname'].'</td>';
   $HTML.='<td>'.($row['notes']!=''?'Y':'N').'</td>';

   $HTML.='</tr>';
}
}else{
    $HTML='No Data Found';
}
$HTML .= '</table></div>';

if($count > 0){
	  $paginationCount=getPagination($count);
}


$HTML .= '';
//Buttons
if($count > 0){
 
	$HTML .='<div style="display:table; margin:0 auto;"><ul class="tsc_pagination tsc_paginationC tsc_paginationC01">' .
	'<li class="first link" id="first">' .
		'<a  href="javascript:void(0)" onclick="changePagination(\'0\',\'first\',\'inventory\')"><<</a>' .
	'</li>';
	if($paginationCount > 18) {
		$bottomPage = 0;
		$topPage = 18;
		if($id > 9) {
			$bottomPage = $id-8;
			$HTML .= '<li>...</li>';
			if($paginationCount < $id+9) {
				$topPage = $paginationCount;
				$bottomPage = $paginationCount - 18;
			} else  {
				$topPage = $id + 9;
				
			}
		}
	} else {
		$bottomPage = 0;
		$topPage = $paginationCount;
	}
	for($i=$bottomPage;$i<$topPage;$i++){
 
		$HTML .='<li id="'.$i.'_no" class="link">' .
		  '<a  href="javascript:void(0)" onclick="changePagination(\''.$i.'\',\''.$i.'_no\',\'inventory\')">' .($i+1) .
		  '</a>' .
	'</li>';
	}
	if($paginationCount > 18) {
		if($id < $paginationCount-9) {
			$HTML .= '<li>...</li>';
		}
	}
	$HTML .='<li class="last link" id="last">' .
		 '<a href="javascript:void(0)" onclick="changePagination(\''.($paginationCount-1).'\',\'last\',\'inventory\')">>></a>' .
	'</li>' .
'</ul></div>';
}
echo $HTML;
?>
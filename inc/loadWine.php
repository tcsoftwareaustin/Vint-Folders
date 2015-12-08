<?php
include_once('init.php');
include_once('functions.php');

if(isset($_POST['pageId']) && !empty($_POST['pageId'])){
   $id=$_POST['pageId'];
}else{
   $id='0';
}


$pageLimit=PAGE_PER_NO*$id;
$query="select * from wine";
$where="";
if(isset($_POST['query']) && $_POST['query']!='all') {
	$where .= ' where upc like \'%'.$_POST['query'].'%\' || type like \'%'.$_POST['query'].'%\' || name like \'%'.$_POST['query'].'%\' || vintage like \'%'.$_POST['query'].'%\'';
}

$order=" order by " . $_POST['sort'] . " " . $_POST['order'];// . " limit $pageLimit,".PAGE_PER_NO;

$query.=$where;
$res=$dbh->query($query.$order);
$count=$res->rowCount();
$HTML='<div id="wine_list"><table style="margin:15px auto;">';
$HTML.='<tr><th style="width:95px;" onClick="sortWine(\'upc\')">UPC/EAN</th><th style="width:185px"onClick="sortWine(\'type\')">Type</th><th style="width:230px" onClick="sortWine(\'name\')">Name</th><th style="width:45px" onClick="sortWine(\'vintage\')">Vintage</th><th style="width:110px" onClick="sortWine(\'cost\')">Regular Cost</th><th style="width:175px" onClick="sortWine(\'sell_price\')">Regular Selling Price</th></tr>';
if($count > 0){
while($row = $res->fetch(PDO::FETCH_ASSOC)){
   $post=$row['id'];
   $link=$row['name'];
   $HTML.='<tr onclick="window.location.assign(\'/displaywine/' . $row['upc'] . '/\')">';
   $HTML.='<td>'.$row['upc'].'</td>';
   $HTML.='<td>'.$row['type'].'</td>';
   $HTML.='<td>'.(strlen($row['name']) > 34?substr($row['name'],0,34) . '...':$row['name'] ).'</td>';
   $HTML.='<td>'.$row['vintage'].'</td>';
   $HTML.='<td>'.$row['cost'].'</td>';
   $HTML.='<td>'.$row['sell_price'].'</td>';
   $HTML.='</tr>';
}
}else{
    $HTML='No Data Found';
}
$HTML .= '</table></div>';
/*				$res=$dbh->query($query);
				$count=$res->rowCount();
				if($count > 0){
					  $paginationCount=getPagination($count);
				}
				
				
				$HTML .= '';
				//Buttons
				if($count > 0){
				 
					$HTML .='<div style="display:table; margin:0 auto;"><ul class="tsc_pagination tsc_paginationC tsc_paginationC01">' .
					'<li class="first link" id="first">' .
						'<a  href="javascript:void(0)" onclick="changePagination(\'0\',\'first\',\'wine\')"><<</a>' .
					'</li>';
					if($paginationCount > 14) {
						$bottomPage = 0;
						$topPage = 14;
						if($id > 7) {
							$bottomPage = $id-6;
							$HTML .= '<li>...</li>';
							if($paginationCount < $id+7) {
								$topPage = $paginationCount;
								$bottomPage = $paginationCount - 14;
							} else  {
								$topPage = $id + 7;
								
							}
						}
					} else {
						$bottomPage = 0;
						$topPage = $paginationCount;
					}
					for($i=$bottomPage;$i<$topPage;$i++){
				 
						$HTML .='<li id="'.$i.'_no" class="link">' .
						  '<a  href="javascript:void(0)" ' . ($id == $i?' class="current"':'') . ' onclick="changePagination(\''.$i.'\',\''.$i.'_no\',\'wine\')">' .($i+1) .
						  '</a>' .
					'</li>';
					}
				 	if($paginationCount > 18) {
						if($id < $paginationCount-9) {
							$HTML .= '<li>...</li>';
						}
					}
					$HTML .='<li class="last link" id="last">' .
						 '<a href="javascript:void(0)" onclick="changePagination(\''.($paginationCount-1).'\',\'last\',\'wine\')">>></a>' .
					'</li>' .
					//'<li class="flash"></li>' .
				'</ul></div>';
				}*/
echo $HTML;
?>
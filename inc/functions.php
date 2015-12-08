<?php
define('PAGE_PER_NO',10); // number of results per page.
function show_user_info() {
	echo $_SESSION['location'];

}
function showMenu() {
echo '<ul>' .
		'<a href="/"><li>Home</li></a>' .
		  '<li>' .
			'Inventory' .
			'<ul>' .
			  '<a href="/addinventory/"><li>Add Inventory</li></a>' .
			  '<a href="/removeinventory/"><li>Remove Inventory</li></a>' .
			  '<a href="/transferinventory/"><li class="last_menu_item">Transfer Inventory</li></a>' .
			'</ul>' .
		  '</li>' .
		  '<li>' .
			'Wine' . 
			'<ul>' . 
				'<a href="/addwine/"><li>Add Wine</li></a>' . 
				'<a href="/showwine/"><li class="last_menu_item">Show Wines</li></a>' .
			'</ul>' . 
		  '</li>' .
		  '<li>Reports' .
				'<ul>' .
					'<a href="/upcsummary/"><li>Summary by UPC</li></a>' .
					'<a href="/completeinventory/"><li class="last_menu_item">Complete Inventory</li></a>' .
				'</ul>'.
			'</li>' .
			(isset($_SESSION['location'])?'<a href="/logout"><li>Logout</li></a>':'') . 
				
		'</ul>';
}

function getPagination($count){
      $paginationCount= floor($count / PAGE_PER_NO);
      $paginationModCount= $count % PAGE_PER_NO;
      if(!empty($paginationModCount)){
               $paginationCount++;
      }
      return $paginationCount;
}

function showLocationSelect($dbh, $args = null) {
	if(!is_null($args) && isset($args['custom_id'])) {
		$id = $args['custom_id'];
	}
	$results = $dbh->query('SELECT * FROM locations');
	$html = '<select name="' . (isset($id)?$id:'location') . '" id="' . (isset($id)?$id:'location') . '" onChange="findWine()">';
	while($row = $results->fetch(PDO::FETCH_ASSOC)) {
		$html .= '<option value="' . $row['id'] . '"' . ($_SESSION['location']==$row['id']?' selected':'') . '>' . $row['locname'] . '</option>';
	}
	$html .= '</select>';
	return $html;
}
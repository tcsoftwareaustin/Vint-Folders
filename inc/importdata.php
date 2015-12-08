<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('init.php');

$file = fopen("winedata.txt","r");
$i = 0;
while(!feof($file) && $i <= 100000) {
	$arr = fgetcsv($file);
	if($i==0) {
	
	} else {
		$dbh->query("INSERT INTO wine (upc, type, name, cost, vintage, sell_price) VALUES ('" . $arr['0'] . "','" . $arr['1'] . "','" . $arr['2'] . "','" . $arr['5'] . "','" . $arr['3'] . "','" . $arr['6'] . "')");
		$id = $dbh->lastInsertId();
		$dbh->query("INSERT INTO inventory(wine_id, quantity, cost) VALUES ('" . $id . "','" . $arr['4'] . "','" . $arr['5'] . "')");
	}
	$i++;
}
fclose($file);
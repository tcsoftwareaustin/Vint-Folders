<?php
require_once('inc/init.php');
require_once('inc/class.wine.php');

echo 'Hello Wine Enthusiasts.<br>';
$dbh->query("DELETE FROM wine");
$dbh->query("DELETE FROM inventory");
$newWine = new Wine($dbh);

$newWine->setUPC('1541948561658');
$newWine->setName('Pinot Grigio');
$newWine->setVintage('1954');
$newWine->setSellPrice('150.00');

$id = $newWine->writeWineToTable();
echo $id . '<br>';
$newWine->setID($id);
$id = $newWine->addInventory(10,15.50,'Bought From Bob');
echo $id . '<br>';
$id = $newWine->removeInventory(5, 45.50, 'Sold To James');
echo $id;
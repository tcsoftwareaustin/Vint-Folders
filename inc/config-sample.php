<?php
$db = Array();
$db['server'] = 'localhost';
$db['database'] = 'wine_cellar';
$db['user'] = 'wine';
$db['password'] = 'wine';
try {
$dbh = new PDO('mysql:host=' . $db['server'] . ';dbname=' . $db['database'], $db['user'], $db['password'], array(PDO::ATTR_PERSISTENT => false));
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . ".";
die();
}
$rootDir = $_SERVER['HTTP_HOST'];
$tmpDir = $_SERVER['DOCUMENT_ROOT'] . '/tmp/';
$dbBackupDir = $_SERVER['DOCUMENT_ROOT'] . '/dbBackup/';
$dbBackupWebDir = $_SERVER['HTTP_HOST'] . '/dbBackup/';

<?
if(!is_dir("dbBackup")) {
	mkdir("dbBackup",0777);
}
$dbh = null;
if(isset($_GET['force'])) {
	$configFile = $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
	unlink($configFile);
}
if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/inc/config.php')) {
	require_once('inc/config.php');
	try {
		$dbh = new PDO('mysql:host=' . $db['server'] . ';dbname=' . $db['database'], $db['user'], $db['password'], array(PDO::ATTR_PERSISTENT => false));
	} catch (PDOException $e) {
		$dbh = null;
	}
} else if(isset($_POST['function']) && $_POST['function'] == 'installNow') {
	
	$db = Array();
	$dbServer = $_POST['server'];
	$dbDatabase = $_POST['database'];
	$dbUser = $_POST['user'];
	$dbPassword = $_POST['password'];

	try {
		$dbh = new PDO('mysql:host=' . $_POST['server'] . ';dbname=' . $_POST['database'], $_POST['user'], $_POST['password'], array(PDO::ATTR_PERSISTENT => false));
	} catch (PDOException $e) {
		print "Error!: " . $e->getMessage() . "<br/>";
		die();
	}
	
	$sql = " DROP TABLE IF EXISTS `inventory`;";
	$sql .= " CREATE TABLE `inventory` (" .
			  "`id` mediumint(9) NOT NULL AUTO_INCREMENT," .
			  "`wine_id` mediumint(9) NOT NULL," .
			  "`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP," .
			  "`quantity` int(11) NOT NULL," .
			  "`cost` decimal(10,2) DEFAULT NULL," .
			  "`sell_price` decimal(10,2) DEFAULT NULL," .
			  "`location` mediumint(9) not null," .
			  "`notes` tinyblob," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM AUTO_INCREMENT=5068 DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `wine`;";
	$sql .= " CREATE TABLE `wine` (" .
			  "`id` mediumint(9) NOT NULL AUTO_INCREMENT," .
			  "`upc` varchar(20) ," .
			  "`type` varchar(50) NOT NULL," .
			  "`name` varchar(255) NOT NULL," .
			  "`vintage` varchar(4) NOT NULL," .
			  "`cost` decimal(10,2) DEFAULT NULL," .
			  "`sell_price` decimal(10,2) DEFAULT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM AUTO_INCREMENT=4818 DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `wine_types`;";
	$sql .= " CREATE TABLE `wine_types` (" .
			  "`id` int(11) NOT NULL AUTO_INCREMENT," .
			  "`name` varchar(50) DEFAULT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=MyISAM DEFAULT CHARSET=latin1;";
	$sql .= " DROP TABLE IF EXISTS `locations`;";
	$sql .= " CREATE TABLE IF NOT EXISTS `locations` (" .
			  "`id` int(11) NOT NULL AUTO_INCREMENT," .
			  "`locname` varchar(50) NOT NULL," .
			  "PRIMARY KEY (`id`)" .
			") ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;";
	$sql .= " INSERT INTO `locations` (`locname`) VALUES" .
			"('Cellar')," .
			"('Bar 1')," .
			"('Bar 2');";
	$res = $dbh->query($sql);
	$dbh = null;
	
	try {
		$dbh = new PDO('mysql:host=' . $dbServer . ';dbname=' . $dbDatabase, $dbUser, $dbPassword, array(PDO::ATTR_PERSISTENT => false));
	} catch (PDOException $e) {
		$dbh = null;
	}
	
	if(!is_null($dbh)) {
		//Create file with these contents
		$fileContents = '<?php' . "\n" . '$db = Array();' . "\n";
		$fileContents .= '$db[\'server\'] = \'' . $dbServer . "';\n";
		$fileContents .= '$db[\'database\'] = \'' . $dbDatabase . "';\n";
		$fileContents .= '$db[\'user\'] = \'' . $dbUser . "';\n";
		$fileContents .= '$db[\'password\'] = \'' . $dbPassword . "';\n";
		$fileContents .= 'try {' . "\n";
			$fileContents .= '$dbh = new PDO(\'mysql:host=\' . $db[\'server\'] . \';dbname=\' . $db[\'database\'], $db[\'user\'], $db[\'password\'], array(PDO::ATTR_PERSISTENT => false));' . "\n";
		$fileContents .= '} catch (PDOException $e) {' . "\n";
			$fileContents .= 'print "Error!: " . $e->getMessage() . ".";' . "\n";
			$fileContents .= 'die();' . "\n";
		$fileContents .= '}' . "\n";
		$fileContents .= '$rootDir = $_SERVER[\'HTTP_HOST\'];' . "\n";
		$fileContents .= '$tmpDir = $_SERVER[\'DOCUMENT_ROOT\'] . \'/tmp/\';' . "\n";
		$fileContents .= '$dbBackupDir = $_SERVER[\'DOCUMENT_ROOT\'] . \'/dbBackup/\';' . "\n";
		$fileContents .= '$dbBackupWebDir = $_SERVER[\'HTTP_HOST\'] . \'/dbBackup/\';' . "\n";
		$file = $_SERVER['DOCUMENT_ROOT'] . '/inc/config.php';
		file_put_contents($file,$fileContents);
	}
}
require_once('inc/functions.php');
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="/css/960/reset.css">
	<link rel="stylesheet" href="/css/960/960_12_col.css">
	<!--<link rel="stylesheet" href="/css/960/text.css">-->
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>	
	<script src="/js/javascript.js"></script>	
<body>
<div id="message"></div>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
		<?if(is_null($dbh)) { ?>
			Welcome to the Wine Cellar Inventory System.  You are seeing this page because you have not yet installed your application.
			<form id="configForm" name="configForm" action="install" method="POST">
				<input type="hidden" id="function" name="function" value="installNow">
				<label for='server'>Server Name:</label><input type="text" id="server" name="server" value="localhost">&nbsp;(If you don't know leave localhost entered)<br><br>
				<!--Please enter the root or admin username and password for your MySQL instance.  This is solely to create a new database and will not be saved.<br>
				<label for='rootUser'>Root User:</label><input type="text" id="rootUser" name="rootUser" value=""><br>
				<label for='rootPassword'>Root Password:</label><input type="text" id="rootPassword" name="rootPassword" value=""><br><br>-->
				Please enter the database name you would like to use.  If you do not desire any specific database name, leave the default.<br>
				<label for='database'>Database Name:</label><input type="text" id="database" name="database" value="wine_cellar"><br><br>
				Please enter the credentials you would like the application to use to connect to MySQL. If you do not desire a specific username, leave the default.<br>
				<label for='user'>Database User:</label><input type="text" id="user" name="user" value="wine"><br>
				<label for='password'>Database Password:</label><input type="text" id="password" name="password" value="wine"><br>
				<input class="submit" type="submit" style="width:75px" value="Install">
			</form>
		<? } else { ?>
			Your installation is completed.
		<? } ?>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

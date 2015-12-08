<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('inc/init.php');
require_once('inc/class.wine.php');
if(isset($_GET['function']) && $_GET['function'] == 'backup') {
	$filename = 'dbbackup' . date('Y-m-d-h-i-s') . '.db';
	$command = 'mysqldump ' . $db['database'] . ' --password=' . $db['password'] . ' --user=' . $db['user'] . ' --single-transaction >' . $dbBackupDir . $filename;
	$result = exec($command,$output);
	header('Location: /admin');
} else if(isset($_GET['function']) && $_GET['function'] == 'restore') {
	$backupFile = $_GET['backup'];
	$command = 'mysql --user=' . $db['user'] . ' --password=' . $db['password'] . ' ' . $db['database'] . ' < ' . $dbBackupDir . $backupFile;
	$result = exec($command,$output);
} else if(isset($_POST['function']) && $_POST['function'] == 'restoreFile') {
	if ($_FILES["file"]["error"] > 0)
	{
		die( "Error: " . $_FILES["file"]["error"] . "<br>");
	}
	else
	{
		$tmpFile = $_FILES["file"]["tmp_name"];
		$command = 'mysql --user=' . $db['user'] . ' --password=' . $db['password'] . ' ' . $db['database'] . ' < ' . $tmpFile;
		$result = exec($command,$output);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="/css/960/reset.css">
	<link rel="stylesheet" href="/css/960/960_12_col.css">
	<!--<link rel="stylesheet" href="/css/960/text.css">-->
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>	
	<script src="/js/javascript.js"></script>
<body>
<div id="message"></div>
	<div id="restoreDatabase" title="Restore Database">
		<form id="restoreForm" name="restoreForm" enctype="multipart/form-data" method="POST">
			<input type="hidden" id="function" name="function" value="restoreFile">
			<label>Backup File:</label><input type="file" id="file" name="file"><br><br>
		</form>
	</div>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
			<form action="/admin"><input type="hidden" id="function" name="function" value="backup"><input type="submit" class="submit" style="width:200px" value="Create Database Backup"></form>
			<input type="button" class="submit" style="width:270px" value="Restore Database Backup From File" onclick="$('#restoreDatabase').dialog('open');"><br>
			<form action="/install" method="GET"><input type="hidden" id="force" name="force" value=""><input class="cancel" type="submit" style="width:200px" value="Delete/Reinstall Database"></form><br><br>
			Click on a database backup file below to download a copy.
			<?  $dir = $dbBackupDir;
				$webDir = $dbBackupWebDir;
				$backups1 = scandir($dir);
				foreach($backups1 AS $file) {
					if($file != '.' && $file != '..' && $file != 'README.txt') {
						echo '<br><a href="/dbBackup/' . $file . '">' . $file . '</a>&nbsp;&nbsp;&nbsp;<a href="/admin?function=restore&backup=' . $file .'">Restore Backup</a>';
					}
				}
			?>
		</div>
		<div id="footer">
		</div>
	</div>
	<script>
	$( '#restoreDatabase' ).dialog({
			autoOpen:false,
			height:260,
			width:550,
			modal:true,
			buttons:{
				"Submit": function(){
					document.getElementById('restoreForm').submit();
					$('#restoreDatabase').dialog("close");
				}
			}
		});
	</script>
</body>
</html>

<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('inc/init.php');
require_once('inc/class.wine.php');
$wine = new Wine($dbh);
$wine->fetchWineByUPC($_GET['upc']);
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
	<script type="text/javascript">
	$( document ).ready(function() {
		changePagination(0,'first','inventory');
	});
	</script>
<body>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content" >	
			<table class="summary" style="margin-left:20px"><tr><td style="padding:5px">UPC:&nbsp;<?echo $_GET['upc'];?></td><td style="padding:5px">Name:&nbsp;<?echo $wine->getName();?></td><td style="padding:5px">Current Inventory:&nbsp;<?echo $wine->getCurrentInventory();?></td></tr></table>
			<input type="hidden" id="upc" name="upc" value="<?echo $_GET['upc'];?>">
			<div class="flash" style="position:absolute;margin-left:450px; z-index:99;top:150px"></div>
			<div id="pageData"></div>
			
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

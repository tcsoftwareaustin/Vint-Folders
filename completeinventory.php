<?
require_once('inc/init.php');
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
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
			<h2>Complete Inventory</h2>
			<form id="form" action="/inc/service.wine.php" target="_blank" method="POST">
				<input type="hidden" id="reportType" name="reportType" value="completeInventory">
				<input type="hidden" id="function" name="function" value="">
				<label style="width:3.2em; font-size:1.3em" for="toDate">As Of:</label><input style="font-size:14px;font-weight:bold;" type="text" id="toDate" name="toDate"><br>
				<input class="submit" style="font-size: 12px;font-weight:bold;" type="button" onClick="generateReport()" value="Generate Report">
				<input class="submit" style="font-size: 12px; font-weight:bold;" type="button" value="Export PDF" onClick="exportReport('exportPDF')">
				<input class="submit" style="font-size: 12px; font-weight:bold;" type="button" value="Export CSV" onClick="exportReport('exportCSV')"><br>
			</form>
			<div id="reportData">
			</div> 
		</div>
		<div id="footer">
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			$('#fromDate').datepicker();
			$('#toDate').datepicker();
		})
	</script>
</body>
</html>

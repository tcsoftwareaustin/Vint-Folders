<?
require_once('inc/init.php');
$res = $dbh->query("SELECT wine.upc, wine.name, inventory.quantity, inventory.datetime, locations.locname FROM inventory left join wine on inventory.wine_id = wine.id LEFT JOIN locations ON locations.id = inventory.location order by inventory.datetime DESC LIMIT 0,7");

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
	<style>
		.ui-datepicker {
			margin-top:5px;
		}
	</style>
<body>
<div id="message"></div>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
		<div id="quickTransaction" title="Quick Transaction">
			<form id="form" name="form">
				<input type="hidden" id="function" name="function" value="">
				<input type="hidden" id="wine_id" name="wine_id" value="">
				<input type="hidden" id="quantity" name="quantity" value="1">
				<input type="hidden" id="notes" name="notes" value="Quick Remove">
				<label>UPC/EAN:</label><input type="number" onChange="findWine()" id="upc" name="upc"><br><br>
				<div id="sellDiv"><label>Adjusted Sell Price:</label><input type="number" id="sell_price" name="sell_price" value="0.00"><br><br></div>
				<div id="costDiv"><label>Adjusted Cost:</label><input type="number" id="cost" name="cost" value="0.00"><br><br></div>
			</form>
		</div>
			<div style="float:left;" id="currentInventory">
			<h4>Recent Transactions</h4>
			<table border="0">
				<tr><th>UPC</th><th>Name</th><th>Date</th><th>Quantity</th><th>Location</th></tr>
				<?php
				while($row = $res->fetch(PDO::FETCH_ASSOC)) {
					echo '<tr><td>' . $row['upc'] . '</td><td>' . $row['name'] . '</td><td>' . $row['datetime'] . '</td><td>' . $row['quantity'] . '</td><td>' . $row['locname'] . '</td></tr>';
				}
				?>
			</table>
			</div>
			<div style="float:right">
				<input type="button" class="action" style="width:200px" value="Quickly Add a Bottle" onclick="$('#function').val('addInventory');$('#sellDiv').css('display','none');$('#costDiv').css('display','block');$('#quickTransaction').dialog('open');"><br>
				<input type="button" class="action" style="width:200px" value="Quickly Remove a Bottle" onclick="$('#function').val('removeInventory');$('#costDiv').css('display','none');$('#sellDiv').css('display','block');$('#quickTransaction').dialog('open');">
			</div>
		</div>
		<div id="footer">
		</div>
	</div>
	<script>
	$(function() {
		$( '#quickTransaction' ).dialog({
			autoOpen:false,
			height:260,
			width:550,
			modal:true,
			buttons:{
				"Submit": function(){
					processRequest();
					$('#quickTransaction').dialog("close");
				}
			}
		});
		$('#message').dialog({
			autoOpen:false,
			height:200,
			width:200,
			modal:true,
			buttons:{
				"OK":function(){$('#message').dialog("close");}
				}
		});
	});
	</script>
</body>
</html>

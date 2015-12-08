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
<div id="message"></div>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
		<div id="error" name="error" title="Wine Not Found">
			<p>No wine could be found for the UPC/Name you've entered.  Please add the wine and inventory before continuing.</p>
		</div>
			<h2>Remove Current Inventory</h2>
			<form id="form" name="form">
			<input type="hidden" id="function" name="function" value="removeInventory">
			<input type="hidden" id="wine_id" name="wine_id" value="">
			<div id="top3"><label for="upc">UPC or Name:</label><input type="number" onChange="findWine()" id="upc" name="upc"><span id="name"></span><br>
			<label for="sell_price">Sell Price:</label><input type="number" id="sell_price" name="sell_price" value="0.00"><br>
			<label for="quantity">Quantity Removed:</label><input type="number" id="quantity" name="quantity" value="1"><br>
			<label for="location">Location:</label><?php echo showLocationSelect($dbh); ?><br></div>
			<div id="wineName"></div>
			<label style="vertical-align:top" for="notes">Notes:</label><textarea rows="8" cols="50" id="notes" name="notes"></textarea><br>
			<input type="button" class="submit" value="Remove Inventory" onClick="processRequest();">&nbsp;<input class="cancel" type="button" value="Reset">
			</form>
		</div>
		<div id="footer">
		</div>
	</div>
	<script>
	$(function() {
		$( '#error' ).dialog({
			autoOpen:false,
			height:225,
			width:450,
			modal:true,
			buttons:{
				"Add Wine": function(){
					window.location.assign('/addwine/');
				},
				"Enter New UPC/EAN": function(){
					$('#upc').val('');
					$('#error').dialog('close');
					$('#upc').focus();
					$('#wineName').css('display','none');
				}
			}
		});
		
		$('#message').dialog({
			autoOpen:false,
			height:250,
			width:400,
			modal:true,
			buttons:{
				"OK":function(){$('#message').dialog("close");}
				}
		});
	});
	</script>
</body>
</html>

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
			<h2>Add Wine</h2>
			<form id="form" name="form">
			<input type="hidden" id="function" name="function" value="addWine">
			<label for="upc">UPC or Name:</label><input type="number" id="upc" name="upc"><br>
			<label for="upc">Type:</label><select class="select" id="type" name="type">
			<option value="Chardonnay">Chardonnay</option>
			<option value="Fume/Sauvignon Blanc">Fume/Sauvignon Blanc</option>
			<option value="Pinot Grigio">Pinot Grigio</option>
			<option value="White Zinfandel/Rose">White Zinfandel/Rose</option>
			<option value="Other Whites">Other Whites</option>
			<option value="Dessert">Dessert</option>
			<option value="Sparkling Wine/Champagne">Sparkling Wine/Champagne</option>
			<option value="Cabernet Sauvignon">Cabernet Sauvignon</option>
			<option value="Meritage Blends">Meritage Blends</option>
			<option value="Zinfandel">Zinfandel</option>
			<option value="Sangiovese">Sangiovese</option>
			<option value="Shiraz/Petite Sirah">Shiraz/Petite Sirah</option>
			<option value="Merlot">Merlot</option>
			<option value="Pinot Noir">Pinot Noir</option>
			<option value="Rhone">Rhone</option>
			<option value="Other Reds">Other Reds</option>
			<option value="Ports">Ports</option>
			<option value="Misc">Misc</option>
			<option value="Pop/Water">Pop/Water</option>
			</select><br>
			<label for="name">Name:</label><input type="text" style="width:400px" id="name" name="name"><br>
			<label for="vintage">Vintage:</label><input type="number" id="vintage" name="vintage" value="1900"><br>
			<label for="cost">Cost:</label><input type="number" id="cost" name="cost" value="0.00"><br>
			<label for="sell_price">Sell Price:</label><input type="number" id="sell_price" name="sell_price" value="0.00"><br>
			<label for="initial_inventory">Quantity:</label><input type="number" id="initial_inventory" name="initial_inventory" value="1"><br>
			<label for="location">Location:</label><?php echo showLocationSelect($dbh); ?><br>
			<input type="button" class="submit" value="Add Wine" onClick="processRequest()">&nbsp;<input class="cancel" type="button" value="Reset">
			</form>
		</div>
		<div id="footer">
		</div>
	</div>
		<script>
	$(function() {
		
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

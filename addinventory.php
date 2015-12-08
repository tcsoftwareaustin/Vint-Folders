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
			<div id="newWineDialog" name="newWineDialog" title="Add Wine">
				<p>No wine could be found for this UPC.  Please add new wine using the form below before adding any inventory.</p>
				<form id="form">
					<label>UPC/EAN:</label><span id="newUPC"></span><br>
					<input type="hidden" id="new_upc" name="new_upc" value="">
					<label>Type:</label><select id="type" name="type">
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
					<label for="name">Name:</label><input type="text" id="new_name" name="new_name"><br>
					<label for="vintage">Vintage:</label><input type="text" id="new_vintage" name="new+vintage" value="1900"><br>
					<label for="cost">Regular Cost:</label><input type="text" id="new_cost" name="new_cost" value="0.00"><br>
					<label for="sell_price">Regular Selling Price:</label><input type="text" id="new_sell_price" name="new_sell_price" value="0.00"><br>
				</form>
			</div>
			<h2>Add to Existing Inventory</h2>
			<form id="form" name="form">
			<input type="hidden" id="function" name="function" value="addInventory">
			<input type="hidden" id="wine_id" name="wine_id" value="">
			<div id="top3"><label for="upc">UPC or Name:</label><input type="number" onChange="findWine()" id="upc" name="upc"><span id="wineName"></span><br>
			<label for="cost">Cost:</label><input type="number" id="cost" name="cost" value="0.00"><br>
			<label for="quantity">How Many Are You Adding?:</label><input type="number" id="quantity" name="quantity" value="1"><br>
			<label for="location">Where To?:</label><?php echo showLocationSelect($dbh); ?><br>
			</div>			
			<div id="wineName"></div>
			<label style="vertical-align:top" for="notes">Notes:</label><textarea rows="8" cols="50" id="notes" name="notes"></textarea><br>
			<input type="button" class="submit" value="Add to Existing Inventory" onClick="processRequest()">&nbsp;<input class="cancel" type="button" value="Reset">
			</form>
		</div>
		<div id="footer">
		</div>
	</div>
	
	<script>
	$(function() {
		$( '#newWineDialog' ).dialog({
			autoOpen:false,
			height:425,
			width:550,
			modal:true,
			buttons:{
				"Add Wine": function(){
					$.ajax({
						type: "POST",
						url: "/inc/service.wine.php",
						data: {
							"function":"addWine",
							upc: $('#new_upc').val(),
							name: $('#new_name').val(),
							vintage: $('#new_vintage').val(),
							cost: $('#new_cost').val(),
							sell_price: $('#new_sell_price').val(),
							type: $('#type').val()
						},
						success: function(msg) {
							$('#cost').val($('#new_cost').val());
							$('#wine_id').val(msg);
							$('#newWineDialog').dialog('close');
							findWine('add');
						}		
					});
				},
				"Cancel": function(){$('#newWineDialog').dialog("close");}
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
	$('#upc').focusout(function(){
		$('#results').css('display','none');
		$('#results').css('height','0px');
	});
	
	</script>
</body>
</html>

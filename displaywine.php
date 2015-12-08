<?
require_once('inc/init.php');
require_once('inc/class.wine.php');
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
			<?php 
				$wine = new Wine($dbh);
				$wine->fetchWineByUPC($_GET['upc']);
			?>
			<div id="edit" style="display:none">
			<h2>Edit: <span id="editUPC"><?php echo $_GET['upc'];?></span></h2>
			<form id="form" name="form">
			<input type="hidden" id="function" name="function" value="updateWine">
			<input type="hidden" id="wine_id" name="wine_id" value="<? echo $wine->getID();?>">
			<label for="name">UPC/EAN:</label><input type="text" style="width:400px" id="upc" name="upc" value="<?echo $wine->getUPC();?>"><br>
			<label for="name">Type:</label><select class="select" id="type" name="type">
			<option value="Chardonnay" <? echo (strtoupper($wine->getType()) == 'CHARDONNAY'?'selected':''); ?>>Chardonnay</option>
			<option value="Fume/Sauvignon Blanc" <? echo (strtoupper($wine->getType()) == 'FUME/SAUVIGNON BLANC'?'selected':''); ?>>Fume/Sauvignon Blanc</option>
			<option value="Pinot Grigio" <? echo (strtoupper($wine->getType()) == 'PINOT GRIGIO'?'selected':''); ?>>Pinot Grigio</option>
			<option value="White Zinfandel/Rose" <? echo (strtoupper($wine->getType()) == 'WHITE ZINFANDEL/ROSE'?'selected':''); ?>>White Zinfandel/Rose</option>
			<option value="Other Whites" <? echo (strtoupper($wine->getType()) == 'OTHER WHITES'?'selected':''); ?>>Other Whites</option>
			<option value="Dessert" <? echo (strtoupper($wine->getType()) == 'DESSERT'?'selected':''); ?>>Dessert</option>
			<option value="Sparkling Wine/Champagne" <? echo (strtoupper($wine->getType()) == 'SPARKLING WINE/CHAMPAGNE'?'selected':''); ?>>Sparkling Wine/Champagne</option>
			<option value="Cabernet Sauvignon" <? echo (strtoupper($wine->getType()) == 'CABERNET SAUVIGNON'?'selected':''); ?>>Cabernet Sauvignon</option>
			<option value="Meritage/Blends" <? echo (strtoupper($wine->getType()) == 'MERITAGE/BLENDS'?'selected':''); ?>>Meritage Blends</option>
			<option value="Zinfandel" <? echo (strtoupper($wine->getType()) == 'ZINFANDEL'?'selected':''); ?>>Zinfandel</option>
			<option value="Sangiovese" <? echo (strtoupper($wine->getType()) == 'SANGIOVESE'?'selected':''); ?>>Sangiovese</option>
			<option value="Shiraz/Petite Sirah" <? echo (strtoupper($wine->getType()) == 'SHIRAZ/PETITE SIRAH'?'selected':''); ?>>Shiraz/Petite Sirah</option>
			<option value="Merlot" <? echo (strtoupper($wine->getType()) == 'MERLOT'?'selected':''); ?>>Merlot</option>
			<option value="Pinot Noir" <? echo (strtoupper($wine->getType()) == 'PINOT NOIR'?'selected':''); ?>>Pinot Noir</option>
			<option value="Rhone" <? echo (strtoupper($wine->getType()) == 'RHONE'?'selected':''); ?>>Rhone</option>
			<option value="Other Reds" <? echo (strtoupper($wine->getType()) == 'OTHER REDS'?'selected':''); ?>>Other Reds</option>
			<option value="Ports" <? echo (strtoupper($wine->getType()) == 'PORTS'?'selected':''); ?>>Ports</option>
			<option value="Misc" <? echo (strtoupper($wine->getType()) == 'MISC'?'selected':''); ?>>Misc</option>
			<option value="Pop/Water" <? echo (strtoupper($wine->getType()) == 'POP/WATER'?'selected':''); ?>>Pop/Water</option>
			</select><br>
			<label for="name">Name:</label><input type="text" style="width:400px" id="name" name="name" value="<?echo $wine->getName();?>"><br>
			<label for="vintage">Vintage:</label><input type="number" id="vintage" name="vintage" value="<?echo $wine->getVintage();?>"><br>
			<label for="cost">Regular Cost:</label><input type="number" id="cost" name="cost" value="<?echo $wine->getCost();?>"><br>
			<label for="sell_price">Regular Sell Price:</label><input type="number" id="sell_price" name="sell_price" value="<?echo $wine->getSellPrice();?>"><br>
			<input class="submit" type="button" value="Update Wine" onClick="processRequest()">&nbsp;<input class="cancel" type="button" value="Reset" onclick="$('#form')[0].reset();">&nbsp;<input class="cancel" type="button" value="Cancel Edit" onClick="$('#edit').css('display','none');$('#show').css('display','block');">
			</form>
			</div>
			<div id="show" style="display:block">
			<h2>Show: <span id="showUPC"><?php echo $_GET['upc'];?></span></h2>
			<label>Type</label><span id="displayType"><?echo $wine->getType();?></span><br>
			<label>Name:</label><span id="displayName"><?echo $wine->getName();?></span><br>
			<label>Vintage:</label><span id="displayVintage"><?echo $wine->getVintage();?></span><br>
			<label>Regular Cost:</label><span id="displayCost"><?echo $wine->getCost();?></span><br>
			<label>Regular Selling Price:</label><span id="displaySellPrice"><?echo $wine->getSellPrice();?></span><br>
			<label>Current Inventory:</label><span id="currentInventory"><?echo $wine->getCurrentInventory();?></span><br>
			<span class="expand-click" onClick="$('#inventory-detail').css('display','block');">Click for Location Details</span><br>
			<div id="inventory-detail" style="display:none;">
				<?php
					$details = $wine->getDetailInventory();
					foreach($details AS $loc=>$qty) {
						echo $loc . ': ' . $qty . '<br>';
					}
				?>
			</div>
			<input class="action" type="button" value="Edit" onClick="$('#show').css('display','none');$('#edit').css('display','block');">&nbsp;<input class="action" type="button" value="Inventory" onClick="window.location.assign('/showinventory/<? echo $_GET['upc'];?>')">
			</div>
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

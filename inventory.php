<?
require_once('inc/init.php');
?>
<html>
<head>
	<link rel="stylesheet" href="/css/960/reset.css">
	<link rel="stylesheet" href="/css/960/960_12_col.css">
	<link rel="stylesheet" href="/css/960/text.css">
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>	
<body>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary:</label><input type="number" id="quickSummary" name="quickSummary"><input type="submit" value="Go"></div>
			</div>
		<div id="content">
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

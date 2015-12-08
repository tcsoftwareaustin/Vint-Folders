<?
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
	<script type="text/javascript">
	$( document ).ready(function() {
		changePagination(0,'0_no','wine');
	});
	function search(){
		var query = $('#search').val();
		window.location.assign('/showwine/'+$('#search').val());
		return false;
	}
	</script>
<body>
	<div class="container_12">
			<div id="nav">
				<? showMenu(); ?>
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Quick Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content" >
		<input type="hidden" id="query" name="query" value="<?echo (isset($_GET['query'])?$_GET['query']:'all');?>">
		<input type="hidden" id="sort" name="sort" value="id">
		<input type="hidden" id="order" name="order" value="ASC">
		<div class="flash" style="position:absolute;margin-left:450px; z-index:99;margin-top:200px;"></div>
		<label style="width:13em;">Find a Wine by Any Variable:</label><input type="text" id="search" name="search" value="<?echo (isset($_GET['query'])?$_GET['query']:'');?>">&nbsp;&nbsp;<input type="button" class="submit" value="Go!" onclick="search()">
		<div id="pageData">
			
		</div>	
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

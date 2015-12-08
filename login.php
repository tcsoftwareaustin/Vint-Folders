<?
require_once('inc/init.php');
$res = $dbh->query("SELECT * From locations");
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
				<div style="float:right;margin-top:12px; margin-right:15px"><label for="quickSummary">Summary By UPC:</label><input type="number" id="quickSummary" name="quickSummary"><input type="button" value="Go" onClick="window.location.assign('/upcsummary/'+$('#quickSummary').val())"></div>
			</div>
		<div id="content">
			<div id="login">
				<form id="login_form" name="login_form" action="/index" method="POST">
				<input type="hidden" id="login_submitted" name="login_submitted" value="1">
				<select id="location" name="location" style="width:300px">
					<option value="">Select Login Location</option>
					<?
						while($row = $res->fetch(PDO::FETCH_ASSOC)) {
							echo '<option value="' . $row['id'] . '">' . $row['locname'] . '</option>';
						}
					?>
				</select><input style="margin-left: 12px;" type="submit" value="Log In" class="submit"/>
				</form>
			</div>
		</div>
		<div id="footer">
		</div>
	</div>
</body>
</html>

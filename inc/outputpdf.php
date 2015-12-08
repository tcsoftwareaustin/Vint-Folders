<?

require_once('init.php');
if(isset($_POST['report_type'])) {
	$reportType = $_POST['report_type'];
} else {
	die('Invalid or No Report Type');
}
?>
<link rel="stylesheet" href="../css/style.css">
<script type="text/javascript">
function generateReport() {
		$.ajax({
		type: "POST",
		url: "/inc/service.wine.php",
		data: {
			"function":<? echo $reportType; ?>,
			"upc":$('#upc').val(),
			"to":$('#toDate').val(),
			"from":$('#fromDate').val()
		},
		success: function(msg) {
			$('#reportData').html(msg);
		}
	});
}
</script>
<?
	$wine = new Wine();
	
	




	/*if(	!isset($_POST['output_type']) || is_null($_POST['output_type']) || 
		!isset($_POST['report_type']) || is_null($_POST['report_type'])) {
		die('Error: Please contact your system administrator. Output or Report Type Not Set');
	} else {
		$output = $_POST['output_type'];
		$report = $_POST['report_type'];
	}
	
	if($output == 'pdf') {
		if($report == 'summaryByUPC') {
			if(!isset($_POST['upc'])) {
				die('No UPC Set');
			}
			$HTML .= 
		}
		
		else if ($report == 'completeInventory') {
		
		}
		
		else {
			die('Error: Please contact your system administrator. Invalid Report Type');
		}
	}
	
	else if ($output == 'csv') {
		if($report == 'summaryByUPC') {
		
		}
		
		else if ($report == 'completeInventory') {
		
		}
		
		else {
			die('Error: Please contact your system administrator. Invalid Report Type');
		}
	}
	
	else {
		die('Error: Please contact your system administrator. Invalid Output Type');
	}
*?
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test View</title>
	<link rel="stylesheet" href="<?php echo base_url().'/assets/css/bootstrap.min.css' ?>">
	<script type="text/javascript" src="<?php echo base_url().'/assets/js/jquery.min.js' ?>"></script>	
	<script>
		
		$(document).ready(function(){
			$("p").click(function(){
				$(this).hide();
			});
		});
	</script>
</head>
<body>
	<h2>This is my view to Ling2x Nako</h2>

	<p>If you click on me, I will disappear.</p>
	<p>Click me away!</p>
	<p>Click me too!</p>
</body>
</html>
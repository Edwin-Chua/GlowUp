<!DOCTYPE html>
<html>
<head>
  	<meta charset="utf-8">
  	<meta name="viewport" content="initial-scale=1, width=device-width">
  	
  	<link rel="stylesheet"  href="css/header2.css">
  	
</head>
<body>
  	
  	<div class="icon-back-circle-parent">
			<img class="icon-back-circle" alt="" src="icons/icon-back-circle.svg">
    		
    		<a href="01homepage.php">
				<img class="frame-child" alt="" src="icons/home.svg">
			</a>
    		
  	</div>

	<!-- Back Button Functionality -->
	<script>
		// This function handles the "Back" button functionality
		function goBack() {
		  var referrer = document.referrer; // Get the referrer (previous page URL)
		  
		  // Redirect based on where the user came from
		  if (referrer.includes('02nutrition.php')) {
			  window.location.href = '02nutrition.php';
		  } else if (referrer.includes('02fitness.php')) {
			  window.location.href = '02fitness.php';
		  } else if (referrer.includes('02health.php')) {
			  window.location.href = '02health.php';
		  } else if (referrer.includes('02selfcare.php')) {
			  window.location.href = '02selfcare.php';
		  } else {
			  // Default redirect if no matching page is found
			  window.location.href = '01homepage.php';
		  }
	  }
	  
	  // Attach the goBack function to the back icon's click event
	  document.querySelector('.icon-back-circle').addEventListener('click', goBack);
	</script>
  	
</body>
</html>
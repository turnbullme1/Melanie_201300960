<?php
//The 'php tag' tells must be used everytime you want to write PHP code.  
//Any code that is not between the php tags will not be seen as PHP code by the interpreter

	
	$aName = filter_input(INPUT_POST, 'aName');
	$phone = filter_input(INPUT_POST, 'phone');
	$complaint = filter_input(INPUT_POST, 'complaint');
	$selection = filter_input(INPUT_POST, 'selection');
	//Use filter_input to get the values the user typed in the three input fields
	//Note that the names here must match the names in the HTML form exactly (name, email, comments)
	
?>
	<!DOCTYPE html>
		<html>
		<head>
			<title>Simple Form Application - PHP</title>
			 <link rel="stylesheet" href="main.css">
		</head>
		<body>
		<p><a href="commentForm.html">Back to HTML Form</a></p>
			<main>				
				<h1>User Submitted Comments</h1>
				<p>Name: <?php print $aName; //Here we enter PHP 'mode' to output the value of the $name variable ?> </p>
				<p>Phone: <?php print $phone; ?> </p>
				<p>Complaint: <?php print $complaint; ?> </p>
				<p>Follow-up: <?php print $selection; ?> </p>
				
			</main>
		</body>
		</html>









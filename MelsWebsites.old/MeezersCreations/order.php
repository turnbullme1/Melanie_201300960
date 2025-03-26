<?php
	$type = filter_input(INPUT_POST, 'type');
	$choose = filter_input(INPUT_POST, 'choose');
	$items = filter_input(INPUT_POST, 'items');
	$description = filter_input(INPUT_POST, 'description');
	$name =  filter_input(INPUT_POST, 'name');
	$email =  filter_input(INPUT_POST, 'email');
	$x = filter_input(INPUT_POST, 'x');
	$x = filter_input(INPUT_POST, 'x');
?>

	<!DOCTYPE html>
		<html>
		<head>
			<title>Meezers Creations Order Form</title>
			 <link rel="stylesheet" href="main.css">
		</head>
		<body>
		<p><a href="order.html">Back to Order Form</a></p>
			<main>				
				<h1>Review Order</h1>
				<p>Name: <?php echo $name; //Here we enter PHP 'mode' to output the value of the $name variable ?> </p>
				<p>Email: <?php echo $email; ?> </p>
				<p>Type: <?php echo $type; ?> </p>
				<p>Choice: <?php echo $choose; ?> </p>
				<p>Items: <?php echo $items; ?> </p>	
				<p>Description: <?php echo $description; ?> </p>				
				
			</main>
		</body>
		</html>









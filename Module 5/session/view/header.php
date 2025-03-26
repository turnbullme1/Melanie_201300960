<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title ?></title>
	
   
</head>

<body>
<nav>
<?php
	if(isset($_SESSION['username']))
	{ ?>
	<nav>	
		<a href="session_example.php">Home</a> |
		<a href="session_example.php?action=forget">Forget Me</a> | 	
		<a href="session_example.php?action=changeform">Change</a>	
	</nav>

	<?php }	else { ?>
	<nav>	
		<a href="session_example.php">Home</a> | 
		<a href="session_example.php?action=forget">Forget Me</a> 
	</nav>
	
	<?php } ?>

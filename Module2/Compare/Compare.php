
<?php
	$num1 = filter_input(INPUT_POST, 'num1');
	$num2 = filter_input(INPUT_POST, 'num2');
	$action = filter_input(INPUT_POST, 'action');

if ($action == "Compare")
{

	
	if ($num1 > $num2) 
		$message = $num1." is bigger than ".$num2;
	elseif ($num1 < $num2)
		//do something else
			$message = $num2." is bigger than ".$num1;
	else
		$message = $num1." and ".num2." are equal."
	?>
	
	
	<!DOCTYPE html>
		<html>
		<head>
			<title>Compare Values</title>
			
		</head>
		<body>
			<main>				
				<h1>Compare Values</h1>
				<p><?php print $message ?></p>
				
			</main>
		</body>
		</html>
	

<?php
}
else{
?>	
	
<!DOCTYPE html>
<html>
<head>
	<title>Compare Values</title>
</head>

<body>
<main>
	<h1>Compare Values</h1>
	<form action="Compare.php" method="post">
		<fieldset>
			<legend>Enter a two numbers </legend>
			<label for="num1">Number 1:</label>
			<input type="text" name="num1" id="num2"><br>

			<label for="num2">Number 2:</label>
			<input type="text" name="num2" id="num2"><br>

	
	<input type="submit" name = "action" value="Compare" >
		</fieldset>
	</form>
</main>
</body>
</html>


<?php 
}
?>
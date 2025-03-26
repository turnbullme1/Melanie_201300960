<?php
//Use filter_input to check the $_POST array to see if the user has sumbmitted the form
$action = filter_input(INPUT_POST, 'action');
if($action == "Print Characters")
{//Form was submitted
	//Capture the data submitted by the user
	$char = filter_input(INPUT_POST, 'char');
	$num = filter_input(INPUT_POST, 'num');
	
	//Create the printed characters
	//First initialize the $characters variable to an empty string
	$characters = "";
	
	//Next use a for loop and the concatenation operator (.) to store the appropriate number of characters 
	for($i = 0; $i < $num; $i++)
		$characters = $characters.$char."<br>";
	//The $characters is now ready for printing as part of the HTML output below
	?>
	<!DOCTYPE html>
		<html>
		<head>
			<title>Print Characters</title>
			
		</head>
		<body>
			<main>				
				<h1>Print Characters</h1>
				<?php echo $characters; ?>
			</main>
		</body>
		</html>
	<?php
}
else
{//Form was not submitted
?>
<!DOCTYPE html>
<html>
<head>
	<title>Using Loops in PHP</title>
</head>

<body>
<main>
	<h1>User Input Form</h1>
	<form action="printchars.php" method="post">
		<fieldset>
			<legend>Enter a character and the number of times to print</legend>
			<label for="char">Character:</label>
			<input type="text" name="char" id="char"><br>

			<label for="num">Number of repetitions:</label>
			<input type="text" name="num" id="num"><br>

	
	<input type="submit" name = "action" value="Print Characters" >
		</fieldset>
	</form>
</main>
</body>
</html>
<?php
}
?>








<?php
//Use filter_input to check the $_POST array to see if the user has sumbmitted the form
$action = filter_input(INPUT_POST, 'action');
if($action == "Print Characters")
{//Form was submitted
	//Capture the data submitted by the user
	$char = filter_input(INPUT_POST, 'char');
	$num = filter_input(INPUT_POST, 'num', FILTER_VALIDATE_INT);
	
	//Create the printed characters
	//First initialize the $characters variable to an empty string
	$characters = "";
	if (empty(trim($char)))
		$characters = "<p>Please enter a character to print.</p>";
	if ($num == FALSE)
		$characters .= "<p>Please enter a valid integer.</p>";
	elseif ($num < 1 OR $num > 200)
		$characters .= "<p>Please enter an integer from 1 to 200.</p>";
	if ($characters == "") {	
		//Next use a for loop and the concatenation operator (.) to store the appropriate number of characters 
		$heading = "Print Characters";
		for($i = 0; $i < $num; $i++)
		$characters = $characters.$char."<br>";
	}
}
	else  {
		$heading = "Error."
	//The $characters is now ready for printing as part of the HTML output below
	?>
	<!DOCTYPE html>
		<html>
		<head>
			<title>Print Characters</title>
			
		</head>
		<body>
			<main>				
				<h1><?php echo $heading; ?></h1>
				<?php echo $characters; ?>
			</main>
		</body>
		</html>
	<?php
}
else {
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
	<form action="printchars2.php" method="post">
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
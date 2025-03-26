
<?php
$action = filter_input(INPUT_POST, 'action');
function calculateResults($q1, $q2, $q3, $q4)
{
	$results = 0;
	if ($q1 == 'Answer 1') 
		$results++;
	if ($q2 == 'Answer 2') 
		$results++;
	if ($q3 == 'Answer 3') 
		$results++;
	if ($q4 == 'Answer 4') 
		$results++;	
	return $results;
}	
if ($action == "Submit Quiz")
{
	$q1 = filter_input(INPUT_POST, 'q1');
	$q2 = filter_input(INPUT_POST, 'q2');
	$q3 = filter_input(INPUT_POST, 'q3');
	$q4 = filter_input(INPUT_POST, 'q4');

	$results = calculateResults($q1, $q2, $q3, $q4);
	
?>
	
	<!DOCTYPE html>
		<html>
		<head>
			<title>Quiz Results</title>
			
		</head>
		<body>
			<main>				
				<h1>Quiz Results</h1>
				<p>You got <?php echo $results ?> out of 4</p>
				<p><a href="quiz.php">Take the quiz again!</a></p>
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
	<title>PHP Quiz</title>
</head>

<body>
<main>
	<h1>Take the quiz</h1>
	<form action="quiz.php" method="post">
		<fieldset>
			<legend>Answer the questions below</legend>
			<p>
			<label >Question 1:</label><br>
			<input type="radio" name="q1" value="Answer 1">Answer One<br>
			<input type="radio" name="q1" value="Answer 2">Answer Two<br>
			<input type="radio" name="q1" value="Answer 3">Answer Three<br>
			<input type="radio" name="q1" value="Answer 4">Answer Four<br>
			</p>
			<p>
			<label >Question 2:</label><br>
			<input type="radio" name="q2" value="Answer 1">Answer One<br>
			<input type="radio" name="q2" value="Answer 2">Answer Two<br>
			<input type="radio" name="q2" value="Answer 3">Answer Three<br>
			<input type="radio" name="q2" value="Answer 4">Answer Four<br>
			</p>
			<p>
			<label >Question 3:</label><br>
			<input type="radio" name="q3" value="Answer 1">Answer One<br>
			<input type="radio" name="q3" value="Answer 2">Answer Two<br>
			<input type="radio" name="q3" value="Answer 3">Answer Three<br>
			<input type="radio" name="q3" value="Answer 4">Answer Four<br>
			</p>
			<p>
			<label >Question 4:</label><br>
			<input type="radio" name="q4" value="Answer 1">Answer One<br>
			<input type="radio" name="q4" value="Answer 2">Answer Two<br>
			<input type="radio" name="q4" value="Answer 3">Answer Three<br>
			<input type="radio" name="q4" value="Answer 4">Answer Four<br>
			</p>
	
	<input type="submit" name = "action" value="Submit Quiz" >
		</fieldset>
	</form>
</main>
</body>
</html>
		
<?php 
}
?>
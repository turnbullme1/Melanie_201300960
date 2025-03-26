<?php
$action = filter_input(INPUT_POST, 'action');

//Function to return Base Amount based on user age and years insured.
function calculateBaseAmount($age, $yearsInsured){
	if (($age + $yearsInsured) >= 16 AND ($age + $yearsInsured) <= 24 )
		return 1000;
	elseif (($age + $yearsInsured) >= 25 AND ($age + $yearsInsured) <= 34 )
		return 600;
	elseif (($age + $yearsInsured) >= 35)
		return 250;
}
//Function to return vehicle premium based on type
function getPremium($vehicleType) {
	if ($vehicleType == 'Compact')
		return 0;
	if ($vehicleType == 'Sedan')
		return 50;
	if ($vehicleType == 'Minivan')
		return 50;	
	if ($vehicleType == 'SUV')
		return 75;
	if ($vehicleType == 'Truck')
		return 125;
	if ($vehicleType == 'Sport')
		return 200;
}	
//Extract data from submitted form
if ($action == "Submit Application") {
	$fullName = filter_input(INPUT_POST, 'fullName');
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
	$yearsInsured = filter_input(INPUT_POST, 'yearsInsured', FILTER_VALIDATE_INT);
	$vehicleType = filter_input(INPUT_POST, 'vehicleType');

//Dynamic Form (Error handling) 
	$message = "";
	if ((empty(trim($fullName))) OR (empty(trim($age))))
		$message = "<p>Please complete all required fields.</p>";
	if ($email === FALSE)
		$message .= "<p>Please enter a valid email.</p>";
	if (($age === FALSE) OR ($yearsInsured === FALSE))
		$message .= "<p>Please enter a valid integer for age and years insured.</p>";
	if ($age == TRUE AND $age < 16)
		$message .= "<p>Age must be 16 or older.</p>";
	if ($vehicleType == NULL)
		$message .= "<p>Please enter a vehicle type.</p>";
	//Calculate monthlyrate from Functions
	$monthlyRate = calculateBaseAmount($age, $yearsInsured) + getPremium($vehicleType);
	
//Dynamic Form Successful Submission
	if ($message == "") {
		$message = '
					<label>Name:</label>
					<span>' . $fullName . '</span><br>

					<label> Email: </label>
					<span>' . $email . '</span><br>

					<label>Age:</label>
					<span>' . $age . '</span><br>
					
					<label>Years Insured:</label>
					<span>' . $yearsInsured . '</span><br>
					
					<label>Vehicle Type:</label>
					<span>' . $vehicleType . '</span><br>

					<label>Monthly Rate:</label>
					<span>' .'$'. $monthlyRate . '</span><br>
					';
}
//Dynamic Form
?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Insurance Quote</title>
				<link rel="stylesheet" href="main.css">
			</head>
			<body>
			<p><a href="insurance_application.php">Back to Application Form</a></p>
				<main>
					<h1>Insurance Quote:</h1>
					<?php echo $message; ?>


				</main>
			</body>
			</html
<?php 

}
else { 
//Form was not submitted
?>
		<!DOCTYPE html>
		<html>
		<head>
			<title>Insurance Application Form </title>
			<link rel="stylesheet" href="main.css">
		</head>
		<body>
			<main>
			<h1>Insurance Application Form</h1>		
			<form action="insurance_application.php" method="post">
				<div id="data">
					<label>Full Name:</label>
					<input type="text" name="fullName">
					<br>

					<label>Email:</label>
					<input type="text" name="email">
					<br>

					<label>Age (minimum 16):</label>
					<input type="text" name="age">
					<br>
					<label>Number of Years Insured:</label>
					<input type="text" name="yearsInsured">
					<br>
					<label>Vehicle Type:</label>
					<select name="vehicleType">
						<option value = ""></option>
						<option value = "Compact">Compact</option>
						<option value = "Sedan">Sedan</option>
						<option value = "Sport">Sport</option>
						<option value = "SUV">SUV</option>
						<option value = "Minivan">Minivan</option>
						<option value = "Truck">Truck</option>  
					</select>
					<br>
				</div>
				<div id="buttons">
					<label>&nbsp;</label>
					<input type="submit" name = "action" value="Submit Application"><br>
				</div>
			</form>
			</main>
		</body>
		</html>
<?php
}
?>		
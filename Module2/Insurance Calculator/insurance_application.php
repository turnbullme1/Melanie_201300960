<?php
//function definitions go outside the main if/else structure
	function calculateBaseAmount($age, $yearsInsured)
	{ 
		$sum = $age + $yearsInsured;
		if($sum > 34)
			$baseRate = 250;
		elseif($sum > 24)
			$baseRate = 600;
		else
			$baseRate = 1000;
		return $baseRate;
	}
	function getPremium($vehicleType)
	{
		if($vehicleType == "Compact")
			$vehiclePremium = 0;
		elseif($vehicleType == "Sedan" || $vehicleType == "Minivan" )
			$vehiclePremium = 50;
		elseif($vehicleType == "SUV")
			$vehiclePremium = 75;	
		elseif($vehicleType == "Truck")
			$vehiclePremium = 125;
		else
			$vehiclePremium = 200;
		return $vehiclePremium;
		
	}
	//capture the user's 'action' 
	$action = filter_input(INPUT_POST, 'action');

		//Test the $action variable - was the form submitted or not?
	if($action == "Submit Application")
	{
		//They have submitted the form
		//capture the form data using filter_input(), 
		//use the FILTER_VALIDATE_INT parameter for integer data
		$fullName = filter_input(INPUT_POST, 'fullName');
		$email = filter_input(INPUT_POST, 'email');
		$age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
		$yearsInsured = filter_input(INPUT_POST, 'yearsInsured', FILTER_VALIDATE_INT);
		$vehicleType = filter_input(INPUT_POST, 'vehicleType');
		
		//initialize the output variable $message to an empty string
		$message = "";
		
		//test for empty text fields
		if(empty($fullName) || empty($email)|| empty($vehicleType))
			$message = "<p>Please complete all required fields</p>";
		//test for non-numeric or empty
		if($age===FALSE || $yearsInsured===FALSE )
			$message .= "<p>Age and Years Insured must be  valid integers</p>";
		else
		{//test for range for age and years insured
			if($age < 16)
				$message .= "<p>Age must be at least 16</p>";
			if($yearsInsured < 0)
				$message .= "<p>Years Insured must be 0 or greater</p>";
		}
		if(empty($message))
		{//if $message is still an empty string, validation passed
			$baseAmount = calculateBaseAmount($age, $yearsInsured);
			$vehiclePremium = getPremium($vehicleType); 
			$totalCost= $baseAmount + $vehiclePremium;
			//Assign the proper HTML to the output variable $message
			//PHP variables can be included inside a string ("") without needing the echo statement 
			$message = "<label>Name:</label>
					<span> $fullName; </span><br>

					<label>Email:</label>
					<span> $email; </span><br>

					<label>Age:</label>
					<span> $age; </span><br>
					
					<label>Years Insured:</label>
					<span> $yearsInsured; </span><br>
					
					<label>Vehicle Type:</label>
					<span>$vehicleType; </span><br>

					<label>Monthly Rate:</label>
					<span>$$totalCost; </span><br>";
			
		}
		else
		{
			//Add the 'error' div and <a> tag for the link to the form
			$message = "<div class='error'>$message</div><p><a href='insurance_application.php'>Back to Application Form</a></p>";
		}
		//The processing is done. 
		//The output variable $message now contains the proper HTML for the client response 
		?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Insurance Quote </title>
				<link rel="stylesheet" href="main.css">
			</head>

			<body>

				<main>
				<h1>Insurance Quote</h1>
				
				<?php echo ($message); ?>
				
				</main>
			</body>
			</html>
		
		<?php	
	
	}
	else
	{//else the $action variable is set to NULL, display the HTML form page
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
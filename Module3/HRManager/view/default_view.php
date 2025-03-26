
<!DOCTYPE html>
		<html>
		<head>
			<title>HR Manager Homepage</title>
			<link rel="stylesheet" href="view/main.css">
		</head>
		<body>
		<header> <h1>HR Manager</h1>
			<nav><a href="hr.php">Home</a><a href="hr.php?action=emplist">All Employees</a>
			<a href="hr.php?action=deptlist">All Departments</a>
			</nav>
			</header>
			
				
				
	<main>
		<h2>Search Options</h2>
		
		<form action="hr.php" method="GET">
			<fieldset>
				<legend>Find Employees by Name </legend>
				<label for="searchname">Name:</label>
				<input type="text" name="searchname" id="searchname"><br>
	
		<input type="submit" name = "action" value="Find Employees by Name" >
			</fieldset>
		</form>
		<form action="hr.php" method="GET">
			<fieldset>
				<legend>Find Employees by Department</legend>
				<label >Department Name:</label>
				<select name = "dept_id">
					<option value = ""></option>
					<option value='1'>Information Technology</option><option value='2'>Finance</option><option value='3'>Human Resources</option><option value='4'>Plant and Operations</option>				</select>
	
		<input type="submit" name = "action" value="Find Employees by Department" >
			</fieldset>
		</form>
	</main>
			<footer><p>&copy;HRManager Inc. 2023 </p>
			<p> CSS by ChatGPT</p>
			</footer>
		</body>
		</html>





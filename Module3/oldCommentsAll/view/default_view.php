<!DOCTYPE html>
	<html>
		<head>
			<title>Simple Form Application - PHP</title>
			<link rel="stylesheet" href="view/main.css">
		</head>
		<body>
			<main>
				<h1>User Input Form</h1>
				<form action="comments.php" method="post">
					<fieldset>
						<legend>Enter Your Comments</legend>
						<label for="name">Name:</label>
						<input type="text" name="name" id="name"><br>

						<label for="email">Email:</label>
						<input type="text" name="email" id="email"><br>

				<label for="comments">Comments</label><br>
				<textarea name="comment" id="comment">
				</textarea><br>
			
				<input type="submit" name = "action" value="Submit Comment" >
				</fieldset>
			</form>
			<?php echo $commentList ?>
		</main>
	</body>
	</html>
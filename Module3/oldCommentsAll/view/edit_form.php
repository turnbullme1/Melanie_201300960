<!DOCTYPE html>
	<html>
		<head>
			<title>Simple Form Application - PHP</title>
			<link rel="stylesheet" href="view/main.css">
		</head>
		<body>
			<main>
				<h1>Update Comment Form</h1>
				<form action="comments.php" method="post">
					<fieldset>
						<legend>Enter Your Comments</legend>
						<label for="name">Name:</label>
						<input type="text" name="name" id="name" value = "<?php echo $name ?>"><br>

						<label for="email">Email:</label>
						<input type="text" name="email" id="email" value = "<?php echo $email ?>"><br>

				<label for="comments">Comments</label><br>
				
				<textarea name="comment" id="comment">
				 <?php echo $comment ?>
				</textarea><br>
				<input type = "hidden" name="id" value = "<?php echo $id ?>">
				<input type="submit" name = "action" value="Update Comment" >
				</fieldset>
			
		</main>
	</body>
	</html>
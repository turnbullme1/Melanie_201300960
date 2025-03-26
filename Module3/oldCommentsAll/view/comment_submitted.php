<!DOCTYPE html>
	<html>

	<head>
		<title>Simple Form Application - PHP</title>
		<link rel="stylesheet" href="view/main.css">
	</head>

	<body>
	<p><a href="comments.php">Back to HTML Form</a></p>
		<main>
			<h1>User Submitted Comments</h1>

			<p>Name: <?php echo $name; ?> </p>
			<p>Email: <?php echo $email; ?> </p>
			<p>Comments: <?php echo $comment; ?></p>
		</main>
	</body>
	</html>
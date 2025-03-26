<?php
//Connection information for finding and connecting to the MySQL server
//Since this is a development environment in XAMPP, we are using 'root' user with no password

 $dsn = 'mysql:host=localhost;dbname=comment_app';
 $dbuser = 'root';
 $dbpass = '';

 //use the variables above to create a new PDO object: $db
 //This variable now contains the information needed to interact
 //with the MySQL database
 $db = new PDO($dsn, $dbuser, $dbpass);
function addComment($name, $email, $comment) 
{
 
	//This function inserts a new record in the table
	  
	global $db; //Make $db accessible inside the function block 
	  
	//The SQL - this is to add a new record in the table 
	//Note the 'placeholders' :col_one, :col_two, :col_three

	$query = 'INSERT INTO forum(name, email, comment) VALUES (:name, :email, :comment)'; 

	//Call the prepare method from the $db object  
	//to setup a prepared (secure) interaction with the database 

	$statement = $db->prepare($query);  

	//'bind' each variable to the placeholders specified in the SQL query 
	$statement->bindValue(':name', $name);  
	$statement->bindValue(':email', $email); 
	$statement->bindValue(':comment', $comment); 
	
	

	//Execute the SQL command 

	$statement->execute();  

	//Our interaction with the DB is done, close the connection to the server  

	$statement->closeCursor();  

	//There is nothing to return from this function

}
function getComments()
{
	//This function finds all records in the specified table
	//and returns them as a result set (2-dimensional associative array)

	global $db; 
	 
	//Setup the SQL statement - no placeholders needed
	$query = 'SELECT * FROM comments';
	$statement = $db->prepare($query);
	$statement->execute();

	//We use the fetchAll() function because we expect
	//that there could be multiple results
	//After this statement, $records contains all the data for the found records
	// stored as a 2-dimensional associative array

	$comments = $statement->fetchAll();
	$statement->closeCursor();

	//return the $records found
	return $comments;
}
function getComment($id)
{
	//This function finds a single record based on the id (primary key) 

	global $db; //Make the $db visible within the function
	  
	//Since we are using a variable $id as part of the SQL statement 
	//we need to use a placeholder  ':id' 

	$query = 'SELECT * FROM comments WHERE id = :id';  
	$statement = $db->prepare($query);  

	//'bind' the variable to the placeholder 
	$statement->bindValue(':id', $id); 
	$statement->execute();  

	//This query will find at most 1 matching record,  
	//so we use the fetch() function here instead of fetchAll()
	 
	$comment = $statement->fetch(); 
	$statement->closeCursor();   
	//return the record- $comment is a 1-dimensional associative array 
	return $comment;
	
}
function updateComment($id, $name, $email, $comment)
{
	global $db; //Make $db accessible inside the function block  

	//The SQL - this is to update an existing entry in the table 
	//Note the 'placeholders' :col_one, :col_two, :col_three, :id 

	$query = 'UPDATE comments SET name= :name, email= :email, comment= :comment WHERE id = :id';    

	//Call the prepare method from the $db object  
	//to setup a prepared (secure) interaction with the database 

	$statement = $db->prepare($query);  

	//'bind' each variable to the placeholders specified in the SQL query 
	$statement->bindValue(':name', $name); 
	$statement->bindValue(':email', $email); 
	$statement->bindValue(':comment', $comment);  
	$statement->bindValue(':id', $id); 

	//Execute the SQL command 

	$statement->execute();  

	//Our interaction with the DB is done, close the connection to the server  

	$statement->closeCursor();  

	//There is nothing to return from this function 
	
}
function deleteComment($id)
{
	//This function deletes an single record from the table based on the id 

	global $db; 

	//setup the query - id comes from the client, so use a placeholder, :id 

	$query = 'DELETE FROM comments WHERE id = :id'; 

	$statement = $db->prepare($query);  

	$statement->bindValue(':id', $id); 

	$statement->execute();  

	$statement->closeCursor();   

	//There is nothing to return from this function
		
}
$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');
if($action == "Submit Comment")
{
    // Capture the data from the form
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $comment= filter_input(INPUT_POST, 'comment');

	
	$message = "";

	if (empty(trim($name)))
	{
		$message .= "<p>Please enter your name to post a comment.</p>";
	}
	if ((empty(trim($email))) || ($email === false))
	{
		$message .= "<p>Enter a valid email address (example@domain.com).</p>";
	}		
	if (empty(trim($comment))) {
		$message .= "<p>Please enter a comment.</p>";
	}

	if (empty($message))
		addComment($name, $email, $comment);
	else
		header("Location:comments.php");


   
	?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Simple Form Application - PHP</title>
		<link rel="stylesheet" href="main.css">
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
	<?php 
}
elseif($action == 'edit')
{
	//User is trying to edit and update a specific comment.
	//This request is sent via the 'GET' method when the user clicks the 'edit' link
	//first capture the 'id' value of the record to be updated
	$id = filter_input(INPUT_GET, 'id');
	
	//Next call the 'getComment' function to get the data for that record
	$commentRecord = getComment($id);
	//Assign the values from the database results to individual variables
	$name = $commentRecord['name'];
	$email = $commentRecord['email'];
	$comment = $commentRecord['comment'];
	//Finally show the 'edit' form populated with the data from the record
	//Note that we use a 'hidden' input element to include the record id in the form
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Simple Form Application - PHP</title>
			<link rel="stylesheet" href="main.css">
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
	<?php
}
elseif($action == 'Update Comment')
{
	// Capture the data from the form
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $comment= filter_input(INPUT_POST, 'comment');
	$id = filter_input(INPUT_POST, 'id');
	//call the updateComment function and send the captured data as parameters
	
	$message = "";

	if (empty(trim($name)))
	{
		$message .= "<p>Please enter your name to post a comment.</p>";
	}
	if ((empty(trim($email))) || ($email === false))
	{
		$message .= "<p>Enter a valid email address (example@domain.com).</p>";
	}		
	if (empty(trim($comment))) {
		$message .= "<p>Please enter a comment.</p>";
	}

	if (empty($message))
		updateComment($id, $name, $email, $comment);
	else
		header("Location:comments.php");


}
elseif($action == 'delete')
{
	//User is trying to delete a secific comment.
	//This request is sent via the 'GET' method when the user clicks the 'delete' link
	//first capture the 'id' value of the record to be deleted
	$id = filter_input(INPUT_GET, 'id');
	//Next call the 'deleteComment' function to delete that record
	deleteComment($id);
	//Redirect the user back to the default view
	header("Location:comments.php");
}
else
{
	//default view: Shows the new comment form and the list of comments
	//
	$comments = getComments();
	
	if($comments != NULL){
		$commentList = "<h2>Comments Found:</h2>";
		foreach($comments as $row){
			$name = $row['name'];
			$email = $row['email'];
			$comment = $row['comment'];
			$posted_date = $row['posted_date'];
			$id = $row['id'];
			$commentList .= "<p>Name: $name  <a href = 'comments.php?action=edit&id=$id'>edit</a>| <a href = 'comments.php?action=delete&id=$id'>delete</a><br> Email: $email <br>$posted_date</p><p>$comment</p><hr>";
		}
		
			
	}
	else
		$commentList = "<h2> No comments found</h2>";
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>Simple Form Application - PHP</title>
			<link rel="stylesheet" href="main.css">
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
	<?php
}
?>

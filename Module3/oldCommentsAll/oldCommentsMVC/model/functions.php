<?php 


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

	$query = 'INSERT INTO comments(name, email, comment, posted) VALUES (:name, :email, :comment, :posted_date)'; 

	//Call the prepare method from the $db object  
	//to setup a prepared (secure) interaction with the database 

	$statement = $db->prepare($query);  

	//'bind' each variable to the placeholders specified in the SQL query 
	$statement->bindValue(':name', $name);  
	$statement->bindValue(':email', $email); 
	$statement->bindValue(':comment', $comment); 
	$statement->bindValue(':posted_date', $posted_date);
	
	

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
function updateComment($id, $name, $email, $comment, $posted_date)
{
	global $db; //Make $db accessible inside the function block  

	//The SQL - this is to update an existing entry in the table 
	//Note the 'placeholders' :col_one, :col_two, :col_three, :id 

	$query = 'UPDATE comments SET name= :name, email= :email, comment= :comment, posted_date= :posted_date WHERE id = :id';    

	//Call the prepare method from the $db object  
	//to setup a prepared (secure) interaction with the database 

	$statement = $db->prepare($query);  

	//'bind' each variable to the placeholders specified in the SQL query 
	$statement->bindValue(':name', $name); 
	$statement->bindValue(':email', $email); 
	$statement->bindValue(':comment', $comment);  
	$statement->bindValue(':id', $id); 
	$statement->bindValue(':posted_date', $posted_date);	

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

?>
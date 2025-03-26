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
function createEmpList($employees) 
{
	//This function creates an employee list from a table as a two dimensional array
	global $db; 
	//The SQL - this is to add a new record in the table 
	//Note the 'placeholders' :col_one, :col_two, :col_three
	$query = 'SELECT (fname, lname, email, dept_id) FROM employees VALUES (:fname, :lname, :email, :dept_id)'; 

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


?>
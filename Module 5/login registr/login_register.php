<?php
session_start();


$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');


if($action == "Home")
{
	view: home.php
}

if($action == "Register" && !Auth)			   //only if not authenticated
{
	view: registration.php
}
else if($action == "Submit Registration")
{
	//capture data
	//validation
	//send data to database with INSERT statement
	view: suggess_page.php
}

if($action == "Login Form" && !Auth)					//only if not authenticated
{
	view: login_form.php
}
else if($action == "Login")
{
	//capture data
	//validation
	//verify data against database w Boolean if exists on table
	//if successful nav buttons change
	//if success, Redirect: home.php
		//else view login with error
}

if($action == "Account" && Auth)					//only if authenticated
{
	//no data to capture
	//select account from database and assign to variables
	view: account.php
}

elseif($action == "Logout" && Auth)					//only if authenticated
{
	$_SESSION = array();
	session_destroy();
	header("Location:login_register.php");
}
else{
	
	//

}

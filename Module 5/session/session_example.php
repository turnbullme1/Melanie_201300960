<?php
session_start();


$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');


if($action == "Set Username")
{
	$username = filter_input(INPUT_POST, 'username');
	$email = filter_input(INPUT_POST, 'email');
	$_SESSION['username'] = $username;
	$_SESSION['email'] = $email;
	header("Location:session_example.php");
}

if ($action == "changeform"){
	$username = $_SESSION['username'];
	$email = $_SESSION['email'];
	include("view/changeform.php");
}

else if($action == "Change")
{
	$username = filter_input(INPUT_POST, 'username');
	$email = filter_input(INPUT_POST, 'email');
	$_SESSION['username'] = $username;
	$_SESSION['email'] = $email;
	header("Location:session_example.php");
}

elseif($action == "forget")
{
	$_SESSION = array();
	session_destroy();
	header("Location:session_example.php");
}
else{
	
	$title="Welcome!";
	include("view/header.php");
	if(isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
		$email = $_SESSION['email'];
		include("view/home.php");	
		
	}
	else
	{
		$username = "Guest";
		include("view/nameform.php");
	}
	include("view/footer.php");

}

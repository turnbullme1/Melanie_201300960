<?php
require("model/functions.php");
 
$action = filter_input(INPUT_POST, 'action');
if($action == NULL)
	$action = filter_input(INPUT_GET, 'action');
if($action == "Submit Comment")
{
    // Capture the data from the form
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $comment= filter_input(INPUT_POST, 'comment');
	addComment($name, $email, $comment);
   
	include("view/comment_submitted.php");
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
	include("view/edit_form.php");
}
elseif($action == 'Update Comment')
{
	// Capture the data from the form
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email');
    $comment= filter_input(INPUT_POST, 'comment');
	$id = filter_input(INPUT_POST, 'id');
	//call the updateComment function and send the captured data as parameters
	updateComment($id, $name, $email, $comment);
	//Redirect the user back to the default view
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
			$id = $row['id'];
			$commentList .= "<p>Name: $name email: $email <a href = 'comments.php?action=edit&id=$id'>edit</a> | <a href = 'comments.php?action=delete&id=$id'>delete</a></p><p>$comment</p><hr>";
		}
		
			
	}
	else
		$commentList = "<h2> No comments found</h2>";
	include("view/default_view.php");
}
?>

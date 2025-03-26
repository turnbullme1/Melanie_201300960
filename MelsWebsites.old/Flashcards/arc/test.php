<?php 
require_once 'includes/db_connect.php';

$query = "SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE featured = 1 LIMIT 5";
$result = $conn->query($query);

$questions = [];

while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

header('Content-Type: application/json');
echo json_encode($questions, JSON_PRETTY_PRINT);
?>

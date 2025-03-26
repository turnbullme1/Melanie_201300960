<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flashcards_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM questions";
$result = $conn->query($sql);

$questions = [];
while ($row = $result->fetch_assoc()) {
    // Decode options if not null
    if ($row['options'] !== null) {
        $row['options'] = json_decode($row['options'], true);
    }
    // Decode answer for Match questions
    if ($row['question_type'] === 'Match') {
        $row['answer'] = json_decode($row['answer'], true);
    }
    $questions[] = $row;
}

$conn->close();

// Write to questions.json
file_put_contents('questions.json', json_encode($questions, JSON_PRETTY_PRINT));

echo "questions.json has been generated.";
?>
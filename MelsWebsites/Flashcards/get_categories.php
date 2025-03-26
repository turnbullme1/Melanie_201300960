<?php
require_once 'includes/db_connect.php';

$course = $_GET['course'] ?? '';

$categories = [];
if ($course !== '') {
    $stmt = $conn->prepare("SELECT DISTINCT category FROM questions WHERE course = ? ORDER BY category");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    $stmt->close();
}

$conn->close();

// Return categories as a JSON response
echo json_encode($categories);
?>

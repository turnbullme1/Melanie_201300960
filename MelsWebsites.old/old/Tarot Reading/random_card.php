<?php
$host = 'localhost';
$dbname = 'tarot';
$username = 'root';
$password = '';

// Establish a connection to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

function getRandomCard($pdo) {
    $stmt = $pdo->query("SELECT name FROM tarot_ccards ORDER BY RAND() LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['name'] : 'No card found';
}

echo getRandomCard($pdo);
?>

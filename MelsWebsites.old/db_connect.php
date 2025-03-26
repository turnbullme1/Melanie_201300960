<?php
$servername = "turnbulls.info";
$username = "turnbull_user";
$password = "P0t@t03s!";
$database = "turnbull_flashcards_db";

try {
    $db = new PDO(
        "mysql:host=$servername;dbname=$database;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Return associative arrays
            PDO::ATTR_EMULATE_PREPARES => false, // Use real prepared statements
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

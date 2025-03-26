<?php
$pageTitle = isset($pageTitle) ? $pageTitle : "Flashcard Learning Platform"; // Default title
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "CET Studying - " . htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <h1>
            <span class="cet-title">CET2025</span><br>
            <span class="page-subtitle"><?php echo htmlspecialchars($pageTitle); ?></span>
        </h1>
<nav>
    <a href="home.php">Home</a>
    <a href="quiz.php">Quizzes</a>
    <a href="flashcards.php">Flashcards</a>
    <a href="code_snippets.php">Code Snippets</a>

</nav>
    </header>
    <main>
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the selected category, defaulting to 'NDG: Cloud and Virtualization Concepts'
$category = isset($_GET['category']) ? $_GET['category'] : 'NDG: Cloud and Virtualization Concepts';

// Define dynamic title
$pageTitle = "Flashcards for " . htmlspecialchars($category);

// Read and decode the JSON file with error handling
$json_file = 'questions.json';
$questions_json = '[]'; // Default to empty array if file fails
if (file_exists($json_file)) {
    $json_data = file_get_contents($json_file);
    $all_questions = json_decode($json_data, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($all_questions)) {
        $questions = array_filter($all_questions, function($q) use ($category) {
            return $q['category'] === $category;
        });
        $questions = array_values($questions);
        $questions_json = json_encode($questions);
    } else {
        echo "<p>Error decoding JSON: " . json_last_error_msg() . "</p>";
    }
} else {
    echo "<p>Error: 'questions.json' file not found.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        header h1 {
            margin: 0;
            font-size: 24px;
        }
        nav {
            margin-top: 15px;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            margin: 0 10px;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #34495e;
            border-radius: 5px;
        }
        main {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        #category-menu {
            margin-bottom: 20px;
        }
        select {
            padding: 10px;
            font-size: 16px;
        }
        .flashcards-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        .flashcard {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .flashcard:hover {
            transform: scale(1.05);
        }
        .flashcard .question {
            font-size: 18px;
            font-weight: bold;
        }
        .flashcard .answer {
            font-size: 16px;
            color: #16a085;
            margin-top: 10px;
        }
        .flashcard .options {
            margin-top: 15px;
            text-align: left;
        }
        .flashcard .options ul, .flashcard .answer ul {
            list-style-type: none;
            padding: 0;
        }
        .flashcard .options li, .flashcard .answer li {
            background-color: #ecf0f1;
            padding: 5px;
            margin: 5px 0;
            border-radius: 4px;
        }
        .navigation {
            text-align: center;
            margin-top: 20px;
        }
        .navigation button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .navigation button:hover {
            background-color: #2980b9;
        }
        .progress {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            width: 100%;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1><?php echo $pageTitle; ?></h1>
        <nav>
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="study.php">Study Guides</a>
            <a href="quiz.php">Quizzes</a>
            <a href="contact.php">Contact</a>
        </nav>
    </header>

    <main>
        <div id="category-menu">
            <form method="get" action="">
                <select name="category" onchange="this.form.submit()">
                    <option value="NDG: Cloud and Virtualization Concepts" <?php echo $category == 'NDG: Cloud and Virtualization Concepts' ? 'selected' : ''; ?>>NDG: Cloud and Virtualization Concepts</option>
                    <option value="VMware Workstation Pro" <?php echo $category == 'VMware Workstation Pro' ? 'selected' : ''; ?>>VMware Workstation Pro</option>
                    <option value="Oracle VM VirtualBox" <?php echo $category == 'Oracle VM VirtualBox' ? 'selected' : ''; ?>>Oracle VM VirtualBox</option>
                    <option value="Microsoft Hyper-V" <?php echo $category == 'Microsoft Hyper-V' ? 'selected' : ''; ?>>Microsoft Hyper-V</option>
                    <option value="VMware ICM Module 1 & 4" <?php echo $category == 'VMware ICM Module 1 & 4' ? 'selected' : ''; ?>>VMware ICM Module 1 & 4</option>
                </select>
            </form>
        </div>

        <div class="flashcards-container" id="flashcard-container">
            <!-- Flashcard will be dynamically inserted here -->
        </div>

        <div class="navigation">
            <button id="prev-btn">Previous</button>
            <button id="next-btn">Next</button>
        </div>

        <div class="progress" id="progress"></div>
    </main>

    <footer>
        <p>© <?php echo date('Y'); ?> Flashcard Learning Platform. All rights reserved.</p>
    </footer>

    <script>
        const questions = <?php echo $questions_json; ?>;
        let currentIndex = 0;

        function displayFlashcard(index) {
            const container = document.getElementById('flashcard-container');
            if (!questions || questions.length === 0) {
                container.innerHTML = '<p>No questions available in this category.</p>';
                return;
            }
            const question = questions[index];
            let optionsHtml = question.options ? '<div class="options"><p><strong>Options:</strong></p><ul>' + question.options.map(option => `<li>${option}</li>`).join('') + '</ul></div>' : '';
            let answerHtml = question.question_type === 'Match' 
                ? '<div class="answer" style="display: none;"><p>Answers:</p><ul>' + question.answer.map(ans => `<li>${ans}</li>`).join('') + '</ul></div>'
                : `<div class="answer" style="display: none;"><p>Answer: ${question.answer}</p></div>`;
            container.innerHTML = `
                <div class="flashcard" onclick="toggleAnswer(this)">
                    <div class="question"><p>${question.question}</p></div>
                    ${answerHtml}
                    ${optionsHtml}
                </div>
            `;
            updateProgress();
        }

        function toggleAnswer(card) {
            const answerDiv = card.querySelector('.answer');
            answerDiv.style.display = (answerDiv.style.display === 'none' || answerDiv.style.display === '') ? 'block' : 'none';
        }

        function updateProgress() {
            const progress = document.getElementById('progress');
            progress.textContent = `Card ${currentIndex + 1} of ${questions.length}`;
        }

        document.getElementById('prev-btn').addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                displayFlashcard(currentIndex);
            }
        });

        document.getElementById('next-btn').addEventListener('click', () => {
            if (currentIndex < questions.length - 1) {
                currentIndex++;
                displayFlashcard(currentIndex);
            }
        });

        if (questions.length > 0) {
            displayFlashcard(currentIndex);
        } else {
            document.getElementById('flashcard-container').innerHTML = '<p>No questions available in this category.</p>';
        }
    </script>
</body>
</html>
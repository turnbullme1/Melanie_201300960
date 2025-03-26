<?php
// Get the selected category, defaulting to 'NDG: Cloud and Virtualization Concepts'
$category = isset($_GET['category']) ? $_GET['category'] : 'NDG: Cloud and Virtualization Concepts';

// Read and decode the JSON file
$json_data = file_get_contents('questions.json');
$all_questions = json_decode($json_data, true);

// Filter questions by the selected category
$questions = array_filter($all_questions, function($q) use ($category) {
    return $q['category'] === $category;
});

// Convert to indexed array for easier iteration
$questions = array_values($questions);

// Encode questions to JSON for JavaScript
$questions_json = json_encode($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        /* Category Menu */
        #category-menu {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 10px;
            font-size: 16px;
        }

        /* Flashcards */
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

        /* Navigation Buttons */
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

        /* Progress Indicator */
        .progress {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Flashcards for <?php echo htmlspecialchars($category); ?></h1>
    
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

    <script>
        // Questions data from PHP
        const questions = <?php echo $questions_json; ?>;
        let currentIndex = 0;

        // Function to display the current flashcard
        function displayFlashcard(index) {
            const container = document.getElementById('flashcard-container');
            const question = questions[index];
            let optionsHtml = '';
            if (question.options) {
                optionsHtml = '<div class="options"><p><strong>Options:</strong></p><ul>';
                question.options.forEach(option => {
                    optionsHtml += `<li>${option}</li>`;
                });
                optionsHtml += '</ul></div>';
            }
            let answerHtml = '';
            if (question.question_type === 'Match') {
                answerHtml = '<div class="answer" style="display: none;"><p>Answers:</p><ul>';
                question.answer.forEach(ans => {
                    answerHtml += `<li>${ans}</li>`;
                });
                answerHtml += '</ul></div>';
            } else {
                answerHtml = `<div class="answer" style="display: none;"><p>Answer: ${question.answer}</p></div>`;
            }
            container.innerHTML = `
                <div class="flashcard" onclick="toggleAnswer(this)">
                    <div class="question"><p>${question.question}</p></div>
                    ${answerHtml}
                    ${optionsHtml}
                </div>
            `;
            updateProgress();
        }

        // Function to toggle the answer visibility
        function toggleAnswer(card) {
            const answerDiv = card.querySelector('.answer');
            if (answerDiv.style.display === 'none' || answerDiv.style.display === '') {
                answerDiv.style.display = 'block';
            } else {
                answerDiv.style.display = 'none';
            }
        }

        // Function to update the progress indicator
        function updateProgress() {
            const progress = document.getElementById('progress');
            progress.textContent = `Card ${currentIndex + 1} of ${questions.length}`;
        }

        // Event listeners for navigation buttons
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

        // Initial display
        if (questions.length > 0) {
            displayFlashcard(currentIndex);
        } else {
            document.getElementById('flashcard-container').innerHTML = '<p>No questions available in this category.</p>';
        }
    </script>
</body>
</html>
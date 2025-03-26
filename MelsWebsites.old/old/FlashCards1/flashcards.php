<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flashcards_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$category = isset($_GET['category']) ? $_GET['category'] : 'NDG: Cloud and Virtualization Concepts'; // Default category

$sql = "SELECT * FROM questions WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

$questions = [];
while ($row = $result->fetch_assoc()) {
    $questions[] = $row;
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashcards</title>
    <link rel="stylesheet" href="styles.css">
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
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            justify-items: center;
        }

        .flashcard {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.2s;
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
        }

        .flashcard .options {
            margin-top: 15px;
            text-align: left;
        }

        .flashcard .options ul {
            list-style-type: none;
            padding: 0;
        }

        .flashcard .options li {
            background-color: #ecf0f1;
            padding: 5px;
            margin: 5px 0;
            border-radius: 4px;
        }

        /* Button for adding a new question */
        #add-question-btn {
            display: block;
            margin: 30px auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        #add-question-btn:hover {
            background-color: #2980b9;
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

    <div class="flashcards-container">
        <?php if (empty($questions)): ?>
            <p>No questions available in this category.</p>
        <?php else: ?>
            <?php foreach ($questions as $question): ?>
                <div class="flashcard">
                    <div class="question" onclick="flipCard(this)">
                        <p><?php echo htmlspecialchars($question['question']); ?></p>
                    </div>
                    <div class="answer" style="display: none;">
                        <p>Answer: <?php echo htmlspecialchars($question['answer']); ?></p>
                    </div>
                    <?php if ($question['options']): ?>
                        <div class="options">
                            <p><strong>Options:</strong></p>
                            <ul>
                                <?php 
                                    $options = json_decode($question['options']);
                                    foreach ($options as $option): 
                                ?>
                                    <li><?php echo htmlspecialchars($option); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <button id="add-question-btn">Add New Flashcard</button>

    <script>
        // Flip the flashcard to reveal the answer
        function flipCard(card) {
            const answerDiv = card.nextElementSibling;
            const questionDiv = card;

            if (answerDiv.style.display === "none") {
                answerDiv.style.display = "block";
                questionDiv.style.display = "none";
            } else {
                answerDiv.style.display = "none";
                questionDiv.style.display = "block";
            }
        }

        // Add a new flashcard (to be implemented as needed)
        document.getElementById("add-question-btn").addEventListener("click", function() {
            alert("This feature is coming soon.");
        });
    </script>
</body>
</html>

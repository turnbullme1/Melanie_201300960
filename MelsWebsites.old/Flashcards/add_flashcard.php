<?php 


// Fetch courses
$courses = $conn->query("SELECT DISTINCT course FROM questions ORDER BY course")->fetch_all(MYSQLI_ASSOC);

// Handle form submission for adding a new flashcard
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course = $_POST['course'];
    $category = $_POST['category'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $explanation = $_POST['explanation']; // Capture explanation input
    $question_type = $_POST['question_type'];
    $options = json_encode($_POST['options'] ?? []);

    $stmt = $conn->prepare("INSERT INTO questions (course, category, question, answer, explanation, question_type, options) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $course, $category, $question, $answer, $explanation, $question_type, $options);

    if ($stmt->execute()) {
        $success_message = "Flashcard successfully added!";
    } else {
        $error_message = "Failed to add flashcard. Please try again.";
    }
    
    $stmt->close();
}

$conn->close();
?>

<h1>Add Flashcard</h1>

<!-- Flashcard Add Form -->
<div class="flashcard-form">
    <form method="POST" action="add_flashcard.php">
        <div>
            <label for="course">Course:</label>
            <select name="course" id="course-select" onchange="loadCategories()" required>
                <option value="select-course">Select a Course</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c['course']) ?>"><?= htmlspecialchars($c['course']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div >
            <label for="category">Category:</label>
            <select name="category" id="category-select" required>
                <option value="select-category">Select a Category</option>
            </select>
        </div>

        <div>
            <label for="question">Question:</label>
            <textarea name="question" id="question" required></textarea>
        </div>

        <div>
            <label for="answer">Answer:</label>
            <textarea name="answer" id="answer" required></textarea>
        </div>



        <div>
            <label for="question_type">Question Type:</label>
            <select name="question_type" id="question_type">
                <option value="Multiple Choice" selected>Multiple Choice</option>
                <option value="Fill">Fill</option>
                <option value="Match">Match</option>
                <option value="Essay">Essay</option>
            </select>
        </div>

        <div id="options-container">
            <label>Options:</label>
            <div>
                <input type="text" name="options[]" placeholder="Option 1" >
                <input type="text" name="options[]" placeholder="Option 2" >
            </div>
            <button type="button" onclick="addOption()">Add Option</button>
        </div>
		
		        <!-- New Explanation Field -->
        <div>
            <label for="explanation">Explanation:</label>
            <textarea name="explanation" id="explanation" placeholder="Enter explanation here..."></textarea>
        </div>

        <div>
            <button type="submit" >Submit Flashcard</button>
        </div>
    </form>

    <?php if (isset($success_message)): ?>
        <div class="success-message"><?= $success_message ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="error-message"><?= $error_message ?></div>
    <?php endif; ?>
</div>

<script>
// Load categories based on selected course
function loadCategories() {
    var course = document.getElementById("course-select").value;
    var categorySelect = document.getElementById("category-select");

    // Clear previous categories
    categorySelect.innerHTML = '<option value="select-category">Select a Category</option>';

    if (course !== "select-course") {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_categories.php?course=' + course, true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                var categories = JSON.parse(xhr.responseText);
                categories.forEach(function(category) {
                    var option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    categorySelect.appendChild(option);
                });
            }
        };
        xhr.send();
    }
}

// Function to add more options for multiple choice questions
function addOption() {
    var container = document.getElementById("options-container");
    var newOption = document.createElement('div');
    newOption.innerHTML = '<input type="text" name="options[]" placeholder="New Option">';
    container.appendChild(newOption);
}
</script>

<?php require_once 'views/footer.php'; ?>

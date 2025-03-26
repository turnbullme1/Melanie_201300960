<?php
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Fetch courses from the database
$courses = $conn->query("SELECT DISTINCT course FROM questions ORDER BY course")->fetch_all(MYSQLI_ASSOC);

// Handle form submission for bulk adding flashcards
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk-submit'])) {
    $bulk_entries = $_POST['bulk_entries'];
    $selected_course = $_POST['course'];
    $selected_category = $_POST['category'];

    // Start a transaction for bulk insert
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO questions (course, category, question, answer, options, explanation) VALUES (?, ?, ?, ?, ?, ?)");

        foreach ($bulk_entries as $entry) {
            $course = $selected_course;
            $category = $selected_category;
            $question = $entry['question'];
            $answer = $entry['answer'];
            $options = isset($entry['options']) && !empty($entry['options']) ? json_encode($entry['options']) : null; // Options are optional
            $explanation = $entry['explanation'] ?? '';

            $stmt->bind_param("ssssss", $course, $category, $question, $answer, $options, $explanation);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: flashcards.php?course=$course&category=$category");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Failed to add flashcards. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<h1>Bulk Add Flashcards</h1>

<!-- Bulk Add Flashcards Form -->
<div class="bulk-add-form">
    <form method="POST" action="bulk_add.php">
        <div class="form-field">
            <label for="course">Course:</label>
            <select id="course" name="course" required onchange="loadCategories()">
                <option value="">Select Course</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c['course']) ?>"><?= htmlspecialchars($c['course']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-field">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
            </select>
        </div>

        <br>

        <div id="bulk-entries-container">
            <!-- Default 3 rows -->
            <?php for ($i = 0; $i < 3; $i++): ?>
                <div class="bulk-entry-row">
                    <div class="inline-form-field">
                        <label for="question-<?= $i ?>">Question:</label>
                        <input type="text" id="question-<?= $i ?>" name="bulk_entries[<?= $i ?>][question]" required>
                    </div>

                    <div class="inline-form-field">
                        <label for="answer-<?= $i ?>">Answer:</label>
                        <input type="text" id="answer-<?= $i ?>" name="bulk_entries[<?= $i ?>][answer]" required>
                    </div>

                    <div class="inline-form-field">
                        <label for="options-<?= $i ?>">Options:</label>
                        <input type="text" id="options-<?= $i ?>-1" name="bulk_entries[<?= $i ?>][options][]" placeholder="Option 1">
                        <input type="text" id="options-<?= $i ?>-2" name="bulk_entries[<?= $i ?>][options][]" placeholder="Option 2">

                        <button type="button" class="add-option-btn" onclick="addOptionField(<?= $i ?>)">+</button>
                    </div>

                    <div class="inline-form-field">
                        <label for="explanation-<?= $i ?>">Explanation:</label>
                        <textarea id="explanation-<?= $i ?>" name="bulk_entries[<?= $i ?>][explanation]"></textarea>
                    </div>
                </div>
            <?php endfor; ?>
        </div>

        <button type="button" onclick="addBulkEntryRow()">Add Another Row</button>
        <button type="submit" name="bulk-submit">Bulk Add Flashcards</button>
    </form>
</div>

<script>
function loadCategories() {
    const course = document.getElementById('course').value;
    const categorySelect = document.getElementById('category');

    // Clear existing categories
    categorySelect.innerHTML = '<option value="">Select Category</option>';

    if (course) {
        // Fetch categories based on selected course
        fetch(`get_categories.php?course=${course}`)
            .then(response => response.json())
            .then(data => {
                // Add categories to the dropdown
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading categories:', error));
    }
}

function addBulkEntryRow() {
    const container = document.getElementById("bulk-entries-container");
    const newRowIndex = container.children.length;

    // Add new row with blank fields for question, answer, etc.
    const newRow = document.createElement("div");
    newRow.classList.add("bulk-entry-row");
    
    newRow.innerHTML = `
        <div class="inline-form-field">
            <label for="question-${newRowIndex}">Question:</label>
            <input type="text" id="question-${newRowIndex}" name="bulk_entries[${newRowIndex}][question]" required>
        </div>

        <div class="inline-form-field">
            <label for="answer-${newRowIndex}">Answer:</label>
            <input type="text" id="answer-${newRowIndex}" name="bulk_entries[${newRowIndex}][answer]" required>
        </div>

        <div class="inline-form-field">
            <label for="options-${newRowIndex}">Options:</label>
            <input type="text" id="options-${newRowIndex}-1" name="bulk_entries[${newRowIndex}][options][]" placeholder="Option 1">
            <input type="text" id="options-${newRowIndex}-2" name="bulk_entries[${newRowIndex}][options][]" placeholder="Option 2">

            <button type="button" class="add-option-btn" onclick="addOptionField(${newRowIndex})">+</button>
        </div>

        <div class="inline-form-field">
            <label for="explanation-${newRowIndex}">Explanation:</label>
            <textarea id="explanation-${newRowIndex}" name="bulk_entries[${newRowIndex}][explanation]"></textarea>
        </div>
    `;
    container.appendChild(newRow);
}

function addOptionField(rowIndex) {
    const optionContainer = document.querySelector(`#options-${rowIndex}-1`).parentElement;
    const newOptionField = document.createElement("input");
    newOptionField.setAttribute("type", "text");
    newOptionField.setAttribute("name", `bulk_entries[${rowIndex}][options][]`);
    newOptionField.setAttribute("placeholder", `Option ${optionContainer.querySelectorAll('input').length + 1}`);
    optionContainer.insertBefore(newOptionField, optionContainer.querySelector(".add-option-btn"));
}
</script>

<?php if (isset($error_message)): ?>
    <div class="error-message"><?= $error_message ?></div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>

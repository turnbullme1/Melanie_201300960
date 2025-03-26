<?php
// views/add_flashcard.php
if (!defined('IN_CONTROLLER')) {
    die('This page cannot be accessed directly.');
}

// Assuming these variables are passed from the controller
// $courses: array of courses from getCourses()
// $course_categories_map: array mapping course names to their categories
// $message: optional message to display (e.g., success or error)
// $username: logged-in user's username (or null if not signed in)
$courses = $courses ?? [];
$course_categories_map = $course_categories_map ?? [];
$message = $message ?? '';
$username = $username ?? null;
?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Add a New Flashcard</h1>
    <div class="flashcard-form">
        <?php if (!empty($message)) echo $message; ?>

        <form method="post">
            <?php if ($username): ?>
                <div class="mb-3">
                    <label class="form-label">Created by:</label>
                    <p class="form-control-static"><?= htmlspecialchars($username) ?></p>
                </div>
            <?php else: ?>
                <div class="mb-3 text-muted">
                    <p>Sign in to associate this flashcard with your account.</p>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="course" class="form-label">Course:</label>
                <select name="course" id="course" class="form-select" required>
                    <option value="">Select a Course</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?= htmlspecialchars($c['course']) ?>"><?= htmlspecialchars($c['course']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category:</label>
                <select name="category" id="category" class="form-select" required>
                    <option value="">Select a Category</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="question" class="form-label">Question:</label>
                <textarea name="question" id="question" class="form-control" required placeholder="Enter the question"></textarea>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">Answer:</label>
                <textarea name="answer" id="answer" class="form-control" required placeholder="Enter the answer"></textarea>
            </div>

            <div class="mb-3">
                <label for="explanation" class="form-label">Explanation (optional):</label>
                <textarea name="explanation" id="explanation" class="form-control" placeholder="Enter an explanation"></textarea>
            </div>

            <div class="mb-3">
                <label for="question_type" class="form-label">Question Type:</label>
                <select name="question_type" id="question_type" class="form-select" onchange="toggleOptions(this)">
                    <option value="Text">Text</option>
                    <option value="Multiple Choice">Multiple Choice</option>
                    <option value="Match">Match</option>
                </select>
            </div>

            <div id="options-container" class="mb-3" style="display: none;">
                <label class="form-label">Options (one per line):</label>
                <textarea name="options[]" id="options" class="form-control" placeholder="Enter options, one per line"></textarea>
                <p class="note text-muted">For Match, use format: "key=value" per line.</p>
            </div>

            <button type="submit" class="btn btn-primary">Add Flashcard</button>
        </form>
    </div>
</div>

<script>
    // Preload course-to-categories mapping from PHP
    const courseCategories = <?php echo json_encode($course_categories_map); ?>;

    // Update category dropdown when course changes
    document.getElementById('course').addEventListener('change', function() {
        const course = this.value;
        const categorySelect = document.getElementById('category');
        
        // Reset category dropdown
        categorySelect.innerHTML = '<option value="">Select a Category</option>';

        // Populate categories if a course is selected and has categories
        if (course && courseCategories[course]) {
            courseCategories[course].forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        }
    });

    // Toggle options visibility based on question type
    function toggleOptions(select) {
        const optionsContainer = document.getElementById('options-container');
        const type = select.value;
        optionsContainer.style.display = (type === 'Multiple Choice' || type === 'Match') ? 'block' : 'none';
    }
</script>
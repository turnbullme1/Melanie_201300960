<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$courses = $courses ?? [];
$course_categories_map = $course_categories_map ?? [];
$selected_course = $selected_course ?? '';
$selected_category = $selected_category ?? '';
$success_count = $_GET['count'] ?? 0;
$success_message = $success_count ? "Successfully added $success_count flashcards!" : '';
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Bulk Add Flashcards</h1>

    <!-- Success/Error Messages -->
    <?php if (isset($message)): ?>
        <div class="alert <?= strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <?php if ($success_message): ?>
        <div class="alert alert-success" id="success-alert"><?= htmlspecialchars($success_message) ?></div>
        <script>
            setTimeout(() => {
                document.getElementById('success-alert').style.display = 'none';
            }, 3000); // Hide success alert after 3 seconds
        </script>
    <?php endif; ?>

    <!-- Course and Category Selectors -->
    <div class="mb-3">
        <button id="add-course-btn" class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#addCourseModal">Add Course</button>
        <button id="add-category-btn" class="btn btn-outline-secondary me-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</button>
        <a href="index.php?action=admin" class="btn btn-secondary float-end">Back to Admin</a>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <label for="course" class="form-label">Course:</label>
            <select name="course" id="course" class="form-select" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c['course']) ?>" <?= $selected_course === $c['course'] ? 'selected' : '' ?>><?= htmlspecialchars($c['course']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label for="category" class="form-label">Category:</label>
            <select name="category" id="category" class="form-select" required>
                <option value="">Select Category</option>
                <?php if ($selected_course && !empty($course_categories_map[$selected_course])): ?>
                    <?php foreach ($course_categories_map[$selected_course] as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= $selected_category === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <!-- Bulk Add Table -->
    <form method="post" action="index.php?action=bulk_add_flashcards" id="bulk-add-form">
        <input type="hidden" name="course" value="<?= htmlspecialchars($selected_course) ?>">
        <input type="hidden" name="category" value="<?= htmlspecialchars($selected_category) ?>">
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Explanation</th>
                    <th>Question Type</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody id="flashcard-rows">
                <?php for ($i = 0; $i < 3; $i++): ?>
                    <tr data-index="<?= $i ?>">
                        <td><textarea name="flashcards[<?= $i ?>][question]" class="form-control" required rows="2"></textarea></td>
                        <td><textarea name="flashcards[<?= $i ?>][answer]" class="form-control" required rows="2"></textarea></td>
                        <td><textarea name="flashcards[<?= $i ?>][explanation]" class="form-control" rows="2"></textarea></td>
                        <td>
                            <select name="flashcards[<?= $i ?>][question_type]" class="form-select question-type-select" onchange="updateOptionsHint(this, <?= $i ?>)">
                                <option value="Text">Text</option>
                                <option value="Multiple Choice">Multiple Choice</option>
                                <option value="Match">Match</option>
                            </select>
                        </td>
                        <td>
                            <textarea name="flashcards[<?= $i ?>][options]" class="form-control options-textarea" style="display: none;" rows="2"></textarea>
                            <small class="form-text text-muted options-hint">Not applicable for Text questions.</small>
                        </td>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <div class="mb-3 text-center">
            <button type="button" class="btn btn-secondary" id="add-row">Add Another Row</button>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Submit Bulk Flashcards</button>
        </div>
    </form>

    <!-- Modals for Add Course and Category -->
    <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addCourseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="index.php?action=add_course">
                    <div class="modal-body">
                        <label for="new_course" class="form-label">Course Name:</label>
                        <input type="text" class="form-control" id="new_course" name="new_course" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="index.php?action=add_category">
                    <div class="modal-body">
                        <label for="course_for_category" class="form-label">Course:</label>
                        <select name="course_for_category" class="form-control mb-3" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?= htmlspecialchars($c['course']) ?>"><?= htmlspecialchars($c['course']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="new_category" class="form-label">Category Name:</label>
                        <input type="text" class="form-control" id="new_category" name="new_category" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Preload course-to-categories mapping from PHP
    const courseCategories = <?php echo json_encode($course_categories_map); ?>;
    let rowIndex = 3; // Start after initial 3 rows

    // Update category dropdown when course changes
    document.getElementById('course').addEventListener('change', function() {
        const course = this.value;
        const categorySelect = document.getElementById('category');
        categorySelect.innerHTML = '<option value="">Select Category</option>';
        if (course && courseCategories[course]) {
            courseCategories[course].forEach(category => {
                const option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        }
        document.querySelector('input[name="course"]').value = course;
        document.querySelector('input[name="category"]').value = categorySelect.value;
    });

    // Update hidden category input when category changes
    document.getElementById('category').addEventListener('change', function() {
        document.querySelector('input[name="category"]').value = this.value;
    });

    // Add new row on button click
    document.getElementById('add-row').addEventListener('click', () => {
        const tbody = document.getElementById('flashcard-rows');
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-index', rowIndex);
        newRow.innerHTML = `
            <td><textarea name="flashcards[${rowIndex}][question]" class="form-control" required rows="2"></textarea></td>
            <td><textarea name="flashcards[${rowIndex}][answer]" class="form-control" required rows="2"></textarea></td>
            <td><textarea name="flashcards[${rowIndex}][explanation]" class="form-control" rows="2"></textarea></td>
            <td>
                <select name="flashcards[${rowIndex}][question_type]" class="form-select question-type-select" onchange="updateOptionsHint(this, ${rowIndex})">
                    <option value="Text">Text</option>
                    <option value="Multiple Choice">Multiple Choice</option>
                    <option value="Match">Match</option>
                </select>
            </td>
            <td>
                <textarea name="flashcards[${rowIndex}][options]" class="form-control options-textarea" style="display: none;" rows="2"></textarea>
                <small class="form-text text-muted options-hint">Not applicable for Text questions.</small>
            </td>
        `;
        tbody.appendChild(newRow);
        rowIndex++;
    });

    // Update options hint and visibility based on question type
    function updateOptionsHint(select, rowIndex) {
        const row = document.querySelector(`tr[data-index="${rowIndex}"]`);
        const optionsTextarea = row.querySelector('.options-textarea');
        const optionsHint = row.querySelector('.options-hint');
        const type = select.value;

        optionsTextarea.style.display = (type === 'Multiple Choice' || type === 'Match') ? 'block' : 'none';
        switch (type) {
            case 'Text':
                optionsHint.textContent = 'Not applicable for Text questions.';
                break;
            case 'Multiple Choice':
                optionsHint.textContent = 'Separate options with Enter (new line).';
                break;
            case 'Match':
                optionsHint.textContent = 'Use "key=value" format, one per line.';
                break;
        }
    }

    // Form validation before submission
    document.getElementById('bulk-add-form').addEventListener('submit', (e) => {
        const rows = document.querySelectorAll('#flashcard-rows tr');
        let hasError = false;
        rows.forEach(row => {
            const question = row.querySelector('[name$="[question]"]').value.trim();
            const answer = row.querySelector('[name$="[answer]"]').value.trim();
            if (!question || !answer) {
                hasError = true;
            }
        });
        const course = document.getElementById('course').value;
        const category = document.getElementById('category').value;
        if (!course || !category) {
            hasError = true;
            alert('Please select both a course and a category.');
        } else if (hasError) {
            alert('Please fill in all required fields (Question and Answer) for each row.');
        }
        if (hasError) {
            e.preventDefault();
        }
    });
</script>
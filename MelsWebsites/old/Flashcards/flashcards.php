<?php
require_once 'includes/header.php';

// Get course and category from GET parameters, default to placeholders
$course = $_GET['course'] ?? 'select-course';
$category = $_GET['category'] ?? 'select-category';

// Set page title based on selection
$pageTitle = ($course === 'select-course' || $category === 'select-category') 
    ? "Flashcards - Select a Course and Category" 
    : "Flashcards for $category";

// Load questions only if valid course and category are selected
$json_file = 'questions.json';
$questions = [];
$categories = [];
if (file_exists($json_file)) {
    $data = json_decode(file_get_contents($json_file), true);
    if (is_array($data)) {
        if ($course !== 'select-course') {
            $categories = array_unique(array_column(array_filter($data, fn($q) => $q['course'] === $course), 'category'));
            if ($category !== 'select-category') {
                $questions = array_values(array_filter($data, fn($q) => $q['course'] === $course && $q['category'] === $category));
            }
        }
    }
}
?>

<div id="course-menu">
    <form method="get">
        <select name="course" onchange="this.form.submit()">
            <option value="select-course" <?= $course === 'select-course' ? 'selected' : '' ?>>Select a Course</option>
            <option value="IT Essentials" <?= $course === 'IT Essentials' ? 'selected' : '' ?>>IT Essentials</option>
            <option value="Cisco Networking" <?= $course === 'Cisco Networking' ? 'selected' : '' ?>>Cisco Networking</option>
            <option value="Systems Security" <?= $course === 'Systems Security' ? 'selected' : '' ?>>Systems Security</option>
            <option value="Systems Analysis" <?= $course === 'Systems Analysis' ? 'selected' : '' ?>>Systems Analysis</option>
            <option value="Systems Virtualization" <?= $course === 'Systems Virtualization' ? 'selected' : '' ?>>Systems Virtualization</option>
        </select>
    </form>
</div>

<div id="category-menu">
    <form method="get">
        <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">
        <select name="category" onchange="this.form.submit()">
            <option value="select-category" <?= $category === 'select-category' ? 'selected' : '' ?>>Select a Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="flashcards-container" id="flashcard-container"></div>
<div class="navigation">
    <button id="prev-btn">Previous</button>
    <button id="next-btn">Next</button>
</div>
<div class="progress" id="progress"></div>

<script id="questions-data" type="application/json"><?= json_encode($questions) ?></script>
<script src="js/flashcards.js"></script>

<?php require_once 'includes/footer.php'; ?>
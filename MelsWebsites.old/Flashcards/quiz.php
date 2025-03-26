<?php
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Get course and category from GET parameters
$course = $_GET['course'] ?? 'select-course';
$category = $_GET['category'] ?? 'select-category';

// Fetch courses and categories
$courses = $conn->query("SELECT DISTINCT course FROM questions ORDER BY course")->fetch_all(MYSQLI_ASSOC);
$categories = [];
if ($course !== 'select-course') {
    $stmt = $conn->prepare("SELECT DISTINCT category FROM questions WHERE course = ? ORDER BY category");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    $stmt->close();
}

// Fetch 10 random multiple-choice questions if course and category are selected
$questions = [];
if ($course !== 'select-course' && $category !== 'select-category') {
    $stmt = $conn->prepare("SELECT id, question, options, answer FROM questions WHERE course = ? AND category = ? AND question_type = 'Multiple Choice' ORDER BY RAND() LIMIT 10");
    $stmt->bind_param("ss", $course, $category);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $row['id'],
            'question' => $row['question'],
            'options' => json_decode($row['options'], true),
            'answer' => $row['answer']
        ];
    }
    $stmt->close();
}

$conn->close();

// Handle quiz submission
$score = null;
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';
if ($submitted) {
    $score = 0;
    foreach ($questions as $q) {
        $userAnswer = $_POST[$q['id']] ?? '';
        if ($userAnswer === $q['answer']) {
            $score++;
        }
    }
}
?>

<h1>Quiz <?php echo ($course === 'select-course' || $category === 'select-category') ? "" : "for $category"; ?></h1>

<div id="course-menu">
    <form method="get">
        <select name="course" onchange="this.form.submit()">
            <option value="select-course" <?= $course === 'select-course' ? 'selected' : '' ?>>Select a Course</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= htmlspecialchars($c['course']) ?>" <?= $course === $c['course'] ? 'selected' : '' ?>><?= htmlspecialchars($c['course']) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div id="category-menu">
    <form method="get">
        <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">
        <select name="category" onchange="this.form.submit()">
            <option value="select-category" <?= $category === 'select-category' ? 'selected' : '' ?>>Select a Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= $category === $cat ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if ($course !== 'select-course' && $category !== 'select-category'): ?>
    <?php if ($submitted): ?>
        <h2>Your Score: <?= $score ?>/10</h2>
    <?php else: ?>
        <form method="post">
            <?php foreach ($questions as $index => $q): ?>
                <div class="quiz-question">
                    <p><strong>Question <?= $index + 1 ?>:</strong> <?= htmlspecialchars($q['question']) ?></p>
                    <?php foreach ($q['options'] as $option): ?>
                        <label>
                            <input type="radio" name="<?= $q['id'] ?>" value="<?= htmlspecialchars($option) ?>" required>
                            <?= htmlspecialchars($option) ?>
                        </label><br>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Quiz</button>
        </form>
    <?php endif; ?>
<?php else: ?>
    <p>Please select a course and category to start the quiz.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
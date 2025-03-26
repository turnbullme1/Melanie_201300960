<?php require_once 'includes/header.php'; ?>
<?php require_once 'includes/db_connect.php'; ?>

<!-- Get course and category from GET parameters -->
<?php 
$course = $_GET['course'] ?? 'select-course';
$category = $_GET['category'] ?? [];

if (!is_array($category)) {
    $category = explode(',', $category); // Convert string to array if necessary
}

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

// Fetch questions if course and category are selected
$questions = [];
if ($course !== 'select-course' && (!empty($category) && $category[0] !== 'all')) {
    $categoryList = implode("','", array_map([$conn, 'real_escape_string'], $category));
    $stmt = $conn->prepare("SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE course = ? AND category IN ('$categoryList')");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $row['id'],
            'question' => $row['question'],
            'options' => $row['options'] ? json_decode($row['options'], true) : null,
            'answer' => $row['question_type'] === 'Match' ? json_decode($row['answer'], true) : $row['answer'],
            'explanation' => $row['explanation'],
            'question_type' => $row['question_type'],
            'image' => $row['image'] ?? null
        ];
    }
    $stmt->close();
} elseif ($course !== 'select-course' && !empty($category) && $category[0] === 'all') {
    // Fetch all categories if 'All' is selected
    $stmt = $conn->prepare("SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE course = ?");
    $stmt->bind_param("s", $course);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $questions[] = [
            'id' => $row['id'],
            'question' => $row['question'],
            'options' => $row['options'] ? json_decode($row['options'], true) : null,
            'answer' => $row['question_type'] === 'Match' ? json_decode($row['answer'], true) : $row['answer'],
            'explanation' => $row['explanation'],
            'question_type' => $row['question_type'],
            'image' => $row['image'] ?? null
        ];
    }
    $stmt->close();
}

$conn->close();
?>

<h1>Flashcards <?php echo ($course === 'select-course' || empty($category)) ? "" : "for " . implode(', ', $category); ?></h1>

<!-- Course Menu -->
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

<!-- Category Menu -->
<div id="category-menu">
    <form method="get">
        <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">
        <select name="category[]" multiple="multiple" onchange="this.form.submit()">
		<option value="all" <?= empty($category) || in_array('all', $category) ? '' : '' ?>>All</option>

            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $category) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<!-- Flashcards Container -->
<div class="flashcards-container" id="flashcard-container"></div>

<!-- Navigation Buttons -->
<div class="navigation">
    <button id="prev-btn">Previous</button>
    <button id="next-btn">Next</button>
</div>

<!-- Add Flashcard Button -->
<div id="add-flashcard-button" style="margin-top: 20px;">
    <a href="add_flashcard.php" class="button" style="padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Add Flashcard</a>
</div>

<!-- Progress -->
<div class="progress" id="progress"></div>

<style>
    .flashcard {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }
    .image img {
        max-width: 100%;
        height: auto;
        max-height: 300px;
        display: block;
        margin: 0 auto;
    }
    .question, .options, .answer, .explanation {
        margin-top: 20px;
    }
    .answer, .explanation {
        display: none;
    }
    .explanation {
        font-style: italic;
        color: #666;
    }
</style>

<script>
    const questions = <?php echo json_encode($questions); ?>;
    let currentIndex = 0;

    function displayFlashcard(index) {
        const container = document.getElementById('flashcard-container');
        if (index < 0 || index >= questions.length || questions.length === 0) {
            container.innerHTML = '<p>No questions available. Please select a course and category.</p>';
            document.getElementById('progress').textContent = '';
            return;
        }
        const q = questions[index];
        let imageHtml = q.image ? `<div class="image"><img src="${q.image}" alt="Question image" /></div>` : '';
        let optionsHtml = q.options ? '<div class="options"><p><strong>Options:</strong></p><ul>' + q.options.map(opt => `<li>${opt}</li>`).join('') + '</ul></div>' : '';
        let answerHtml = q.question_type === 'Match' ? 
            '<div class="answer"><p>Answers:</p><ul>' + q.answer.map(ans => `<li>${ans}</li>`).join('') + '</ul></div>' : 
            `<div class="answer"><p><strong>Answer:</strong> ${q.answer}</p></div>`;
        let explanationHtml = `<div class="explanation"><p><strong>Explanation:</strong> ${q.explanation || "No explanation available."}</p></div>`;
        container.innerHTML = ` 
            <div class="flashcard" onclick="toggleVisibility(this)">
                ${imageHtml}
                <div class="question"><p>${q.question}</p></div>
                ${answerHtml}
                ${optionsHtml}
                ${explanationHtml}
            </div>`;
        updateProgress();
    }

    function toggleVisibility(card) {
        const answerDiv = card.querySelector('.answer');
        const explanationDiv = card.querySelector('.explanation');
        const isAnswerVisible = answerDiv.style.display === 'block';
        
        // Toggle both answer and explanation visibility
        answerDiv.style.display = isAnswerVisible ? 'none' : 'block';
        explanationDiv.style.display = isAnswerVisible ? 'none' : 'block';
    }

    function updateProgress() {
        document.getElementById('progress').textContent = `Card ${currentIndex + 1} of ${questions.length}`;
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
        document.getElementById('flashcard-container').innerHTML = '<p>No questions available. Please select a course and category.</p>';
    }
	

</script>

<?php require_once 'includes/footer.php'; ?>

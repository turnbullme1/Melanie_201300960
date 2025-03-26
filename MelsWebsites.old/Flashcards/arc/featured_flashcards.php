<?php require_once 'includes/header.php'; 
require_once 'includes/db_connect.php';

// Fetch featured questions (featured flag is now set based on id starting with 'sv-')
$questions = [];
$query = "SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE featured = 1";
$result = $conn->query($query);

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

$conn->close();
?>

<h1>Featured Flashcards</h1>

<div class="flashcards-container" id="flashcard-container"></div>

<div class="navigation">
    <button id="prev-btn">Previous</button>
    <button id="next-btn">Next</button>
</div>

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
            container.innerHTML = '<p>No featured questions available.</p>';
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
        document.getElementById('flashcard-container').innerHTML = '<p>No featured questions available.</p>';
    }
</script>

<?php require_once 'includes/footer.php'; ?>

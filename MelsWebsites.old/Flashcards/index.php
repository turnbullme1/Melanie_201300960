<?php 
require_once 'includes/header.php'; 
require_once 'includes/db_connect.php';

// Fetch featured questions where the id starts with 'sv-'
$questions = [];
$query = "SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE featured = 1";
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $decodedOptions = $row['options'] ? json_decode($row['options'], true) : null;
    $decodedAnswer = ($row['question_type'] === 'Match') ? json_decode($row['answer'], true) : $row['answer'];

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("JSON Decode Error: " . json_last_error_msg());
    }

    $questions[] = [
        'id' => $row['id'],
        'question' => $row['question'],
        'options' => $decodedOptions,
        'answer' => $decodedAnswer,
        'explanation' => $row['explanation'],
        'question_type' => $row['question_type'],
        'image' => $row['image'] ?? null
    ];
}

$conn->close();
?>

<h1>Featured Flashcards</h1>
<h2> Virtualization Module 3 - 6 </h2>

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
        cursor: pointer;
    }
    .image img {
        max-width: 100%;
        height: auto;
        max-height: 300px;
        display: block;
        margin: 0 auto;
    }
    .question, .options, .answer, .explaination {
        margin-top: 20px;
    }
    .answer, .explaination {
        display: none;
    }
    .explaination {
        font-style: italic;
        color: #666;
    }
</style>

<script>
    const questions = <?php echo json_encode($questions, JSON_HEX_TAG); ?>;
    console.log("Loaded Questions:", questions); // Debugging output

    let currentIndex = 0;

    function displayFlashcard(index) {
        const container = document.getElementById('flashcard-container');
        if (index < 0 || index >= questions.length || questions.length === 0) {
            container.innerHTML = '<p>No featured questions available.</p>';
            document.getElementById('progress').textContent = '';
            return;
        }
        const q = questions[index];

        // Debugging: Check if image path is correct
        console.log("Image Path:", q.image);

        let imageHtml = q.image ? `<div class="image"><img src="img/${q.image}" alt="Question image" onerror="this.style.border='2px solid red'; console.log('Image failed to load:', this.src);" /></div>` : '';
        let optionsHtml = q.options ? '<div class="options"><p><strong>Options:</strong></p><ul>' + q.options.map(opt => `<li>${opt}</li>`).join('') + '</ul></div>' : '';
        let answerHtml = q.question_type === 'Match' ? 
            '<div class="answer"><p>Answers:</p><ul>' + q.answer.map(ans => `<li>${ans}</li>`).join('') + '</ul></div>' : 
            `<div class="answer"><p><strong>Answer:</strong> ${q.answer}</p></div>`;
        let explainationHtml = `<div class="explaination"><p><strong>Explanation:</strong> ${q.explanation || "No explanation available."}</p></div>`;

        container.innerHTML = `
            <div class="flashcard" onclick="toggleVisibility(this)">
                ${imageHtml}
                <div class="question"><p>${q.question}</p></div>
                ${answerHtml}
                ${optionsHtml}
                ${explainationHtml}
            </div>`;
        updateProgress();
    }

    function toggleVisibility(card) {
        const answerDiv = card.querySelector('.answer');
        const explainationDiv = card.querySelector('.explaination'); // Fixed typo
        
        const isAnswerVisible = answerDiv.style.display === 'block';
        
        answerDiv.style.display = isAnswerVisible ? 'none' : 'block';
        explainationDiv.style.display = isAnswerVisible ? 'none' : 'block';
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

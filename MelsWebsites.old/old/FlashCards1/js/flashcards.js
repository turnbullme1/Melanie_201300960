document.addEventListener('DOMContentLoaded', () => {
    let questions = JSON.parse(document.getElementById('questions-data').textContent);
    let currentIndex = 0;

    const container = document.getElementById('flashcard-container');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const progress = document.getElementById('progress');

    // Fisher-Yates shuffle function
    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    // Shuffle questions on load
    if (questions && questions.length > 0) {
        questions = shuffle(questions);
    }

    function displayFlashcard(index) {
        if (!questions || !questions.length) {
            container.innerHTML = '<p>Please select a course and category to view flashcards.</p>';
            prevBtn.disabled = true;
            nextBtn.disabled = true;
            progress.textContent = '';
            return;
        }
        const q = questions[index];
        const optionsHtml = q.options ? `<div class="options"><p><strong>Options:</strong></p><ul>${q.options.map(opt => `<li>${opt}</li>`).join('')}</ul></div>` : '';
        const answerHtml = q.question_type === 'Match' 
            ? `<div class="answer"><p>Answers:</p><ul>${q.answer.map(ans => `<li>${ans}</li>`).join('')}</ul></div>` 
            : `<div class="answer"><p>Answer: ${Array.isArray(q.answer) ? q.answer.join(', ') : q.answer}</p></div>`;
        container.innerHTML = `
            <div class="flashcard" onclick="toggleAnswer(this)">
                <div class="question"><p>${q.question}</p></div>
                ${answerHtml}
                ${optionsHtml}
            </div>
        `;
        prevBtn.disabled = index === 0;
        nextBtn.disabled = index === questions.length - 1;
        updateProgress();
    }

    window.toggleAnswer = (card) => {
        const answer = card.querySelector('.answer');
        answer.style.display = answer.style.display === 'none' ? 'block' : 'none';
    };

    function updateProgress() {
        progress.textContent = questions.length ? `Card ${currentIndex + 1} of ${questions.length}` : '';
    }

    prevBtn.addEventListener('click', () => {
        if (currentIndex > 0) displayFlashcard(--currentIndex);
    });

    nextBtn.addEventListener('click', () => {
        if (currentIndex < questions.length - 1) displayFlashcard(++currentIndex);
    });

    displayFlashcard(currentIndex);
});
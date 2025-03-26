// js/flashcards.js
document.addEventListener('DOMContentLoaded', () => {
    const questions = JSON.parse(document.getElementById('questions-data').textContent);
    let currentIndex = 0;

    function displayFlashcard(index) {
        const container = document.getElementById('flashcard-container');
        if (index < 0 || index >= questions.length || questions.length === 0) {
            container.innerHTML = '<p>No questions available. Please select a course and category.</p>';
            document.getElementById('progress').textContent = '';
            return;
        }

        const q = questions[index];
        let optionsHtml = q.options ? '<div class="options"><p><strong>Options:</strong></p><ul>' + q.options.map(opt => `<li>${opt}</li>`).join('') + '</ul></div>' : '';
        let answerHtml = q.question_type === 'Match' ? 
            '<div class="answer"><p>Answers:</p><ul>' + q.answer.map(ans => `<li>${ans}</li>`).join('') + '</ul></div>' : 
            `<div class="answer"><p>Answer: ${q.answer}</p></div>`;
        container.innerHTML = `
            <div class="flashcard" onclick="toggleAnswer(this)">
                <div class="question"><p>${q.question}</p></div>
                ${answerHtml}
                ${optionsHtml}
            </div>`;
        updateProgress();
    }

    function toggleAnswer(card) {
        const answerDiv = card.querySelector('.answer');
        answerDiv.style.display = answerDiv.style.display === 'none' || !answerDiv.style.display ? 'block' : 'none';
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

    displayFlashcard(currentIndex);
});
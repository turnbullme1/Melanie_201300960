document.addEventListener('DOMContentLoaded', () => {
    let questions = JSON.parse(document.getElementById('questions-data').textContent);
    let currentIndex = 0;
    let score = 0;

    const container = document.getElementById('quiz-container');
    const submitBtn = document.getElementById('submit-btn');
    const nextBtn = document.getElementById('next-btn');
    const resultDiv = document.getElementById('quiz-result');

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

    function displayQuestion(index) {
        if (!questions || !questions.length) {
            container.innerHTML = '<p>Please select a course and category to start the quiz.</p>';
            submitBtn.disabled = true;
            nextBtn.disabled = true;
            resultDiv.classList.add('hidden');
            return;
        }
        const q = questions[index];
        let inputHtml = '';

        switch (q.question_type) {
            case 'Multiple Choice':
                inputHtml = `<div class="options">${q.options.map((opt, i) => `<label><input type="radio" name="answer" value="${i}"> ${opt}</label>`).join('<br>')}</div>`;
                break;
            case 'Fill':
            case 'Essay':
                inputHtml = `<input type="text" id="text-answer" placeholder="Type your answer">`;
                break;
            case 'Match':
                inputHtml = `<div class="options"><p>Match the following (not interactive yet):</p><ul>${q.options.map(opt => `<li>${opt}</li>`).join('')}</ul></div>`;
                break;
            default:
                inputHtml = '<p>Unsupported question type</p>';
        }

        container.innerHTML = `
            <div class="quiz-question">
                <p><strong>Question ${index + 1} of ${questions.length}:</strong> ${q.question}</p>
                ${inputHtml}
            </div>
        `;
        submitBtn.disabled = false;
        nextBtn.disabled = true;
        resultDiv.classList.add('hidden');
    }

    function checkAnswer() {
        const q = questions[currentIndex];
        let userAnswer;

        switch (q.question_type) {
            case 'Multiple Choice':
                const selected = document.querySelector('input[name="answer"]:checked');
                userAnswer = selected ? q.options[parseInt(selected.value)] : null;
                break;
            case 'Fill':
            case 'Essay':
                userAnswer = document.getElementById('text-answer')?.value.trim() || '';
                break;
            case 'Match':
                userAnswer = null; // Placeholder
                break;
            default:
                userAnswer = null;
        }

        const correct = q.question_type === 'Match' 
            ? false 
            : Array.isArray(q.answer) ? q.answer.includes(userAnswer) : userAnswer === q.answer;

        if (correct) score++;
        submitBtn.disabled = true;
        nextBtn.disabled = false;
        resultDiv.classList.remove('hidden');
        resultDiv.innerHTML = `<p>${correct ? 'Correct!' : 'Incorrect.'} Answer: ${Array.isArray(q.answer) ? q.answer.join(', ') : q.answer}</p>`;
    }

    submitBtn.addEventListener('click', checkAnswer);

    nextBtn.addEventListener('click', () => {
        currentIndex++;
        if (currentIndex < questions.length) {
            displayQuestion(currentIndex);
        } else {
            container.innerHTML = '';
            submitBtn.disabled = true;
            nextBtn.disabled = true;
            resultDiv.innerHTML = `<h2>Quiz Complete!</h2><p>Your score: ${score} out of ${questions.length} (${Math.round((score / questions.length) * 100)}%)</p>`;
        }
    });

    displayQuestion(currentIndex);
});
<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$courses = $courses ?? [];
$course = $course ?? 'select-course';
$category = $category ?? [];
$categories = $categories ?? [];
$flashcards = $flashcards ?? [];
$pageTitle = "Flashcards";
$total_flashcards = count($flashcards);
$current_index = isset($_GET['index']) ? max(0, min((int)$_GET['index'], $total_flashcards - 1)) : 0; // Default to 0, clamp to bounds
?>
<div class="container mt-4">
    <h1 class="text-center mb-4">Flashcards <?php echo ($course === 'select-course' || empty($category)) ? "" : "for " . implode(', ', array_map('htmlspecialchars', $category)); ?></h1>

    <!-- Course Menu -->
    <div id="course-menu" class="mb-3">
        <form method="get">
            <input type="hidden" name="action" value="flashcards">
            <select name="course" class="form-select" onchange="this.form.submit()">
                <option value="select-course" <?= $course === 'select-course' ? 'selected' : '' ?>>Select a Course</option>
                <?php foreach ($courses as $c): ?>
                    <option value="<?= htmlspecialchars($c['course']) ?>" <?= $course === $c['course'] ? 'selected' : '' ?>><?= htmlspecialchars($c['course']) ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- Category Menu -->
    <?php if ($course !== 'select-course'): ?>
        <div id="category-menu" class="mb-3">
            <form method="get">
                <input type="hidden" name="action" value="flashcards">
                <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">
                <select name="category[]" class="form-select" multiple="multiple" onchange="this.form.submit()">
                    <option value="all" <?= in_array('all', $category) ? 'selected' : '' ?>>All</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= in_array($cat, $category) ? 'selected' : '' ?>><?= htmlspecialchars($cat) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    <?php endif; ?>

    <!-- Add Flashcard and Bulk Add Buttons -->
    <div id="add-flashcard-button" class="mb-3 text-center">
        <a href="index.php?action=add%20flashcard" class="btn btn-success me-2">Add Flashcard</a>
        <a href="index.php?action=bulk_add_flashcards" class="btn btn-primary">Bulk Add Flashcards</a>
    </div>

    <!-- Flashcards Container -->
    <div class="flashcards-container">
        <?php if (!empty($flashcards)): ?>
            <?php foreach ($flashcards as $index => $flashcard): ?>
                <div class="flashcard position-relative" id="flashcard-<?= $index ?>" style="<?= $index === $current_index ? '' : 'display: none;' ?>">
                    <!-- Favorite Star -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php
                        $category_param = !empty($category) ? '&category[]=' . implode('&category[]=', array_map('urlencode', $category)) : '';
                        $toggle_url = "index.php?action=toggle_favorite&question_id={$flashcard['id']}&course=" . urlencode($course) . $category_param . "&index=$current_index";
                        ?>
                        <a href="<?= $toggle_url ?>" class="favorite-star position-absolute top-0 end-0 m-2" style="font-size: 1.5em; color: gold; text-decoration: none;">
                            <?= $flashcard['is_favorite'] ? '★' : '☆' ?>
                        </a>
                    <?php endif; ?>
                    <div class="question"><?= htmlspecialchars($flashcard['question']) ?></div>
                    <?php if ($flashcard['image']): ?>
                        <div class="image"><img src="img/<?= htmlspecialchars($flashcard['image']) ?>" alt="Flashcard image" class="img-fluid" /></div>
                    <?php endif; ?>
                    <?php if ($flashcard['options']): ?>
                        <div class="options">
                            <p><strong>Options:</strong></p>
                            <ul>
                                <?php foreach ($flashcard['options'] as $option): ?>
                                    <li><?= htmlspecialchars($option) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <div class="answer" style="display: none;">
                        <?php if ($flashcard['question_type'] === 'Match' && is_array($flashcard['answer'])): ?>
                            <p><strong>Answers:</strong></p>
                            <ul>
                                <?php foreach ($flashcard['answer'] as $ans): ?>
                                    <li><?= htmlspecialchars($ans) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><strong>Answer:</strong> <?= htmlspecialchars($flashcard['answer']) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="explanation" style="display: none;">
                        <p><strong>Explanation:</strong> <?= htmlspecialchars($flashcard['explanation'] ?? "No explanation available.") ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">No flashcards available for this selection.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($flashcards)): ?>
        <div class="navigation text-center mt-3 d-flex justify-content-center align-items-center">
            <button id="prev-btn" class="btn btn-secondary me-3" <?= $current_index <= 0 ? 'disabled' : '' ?>>◄</button>
            <input type="range" id="flashcard-slider" min="0" max="<?= $total_flashcards - 1 ?>" value="<?= $current_index ?>" class="form-range w-50" step="1">
            <button id="next-btn" class="btn btn-secondary ms-3" <?= $current_index >= $total_flashcards - 1 ? 'disabled' : '' ?>>►</button>
        </div>
        <div class="progress text-center mt-2" id="progress">Flashcard <?= $current_index + 1 ?> of <?= $total_flashcards ?></div>

        <script>
            const flashcards = document.querySelectorAll('.flashcard');
            const totalFlashcards = flashcards.length;
            let currentIndex = <?= $current_index ?>;

            function showFlashcard(index) {
                flashcards.forEach((card, i) => {
                    card.style.display = i === index ? 'block' : 'none';
                });
                document.getElementById('progress').textContent = `Flashcard ${index + 1} of ${totalFlashcards}`;
                document.getElementById('flashcard-slider').value = index;
                document.getElementById('prev-btn').disabled = index <= 0;
                document.getElementById('next-btn').disabled = index >= totalFlashcards - 1;
                // Update URL without reloading
                const url = new URL(window.location);
                url.searchParams.set('index', index);
                window.history.pushState({}, '', url);
            }

            // Slider event listener
            document.getElementById('flashcard-slider').addEventListener('input', (e) => {
                currentIndex = parseInt(e.target.value);
                showFlashcard(currentIndex);
            });

            // Previous button event listener
            document.getElementById('prev-btn').addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    showFlashcard(currentIndex);
                }
            });

            // Next button event listener
            document.getElementById('next-btn').addEventListener('click', () => {
                if (currentIndex < totalFlashcards - 1) {
                    currentIndex++;
                    showFlashcard(currentIndex);
                }
            });

            // Toggle answer and explanation on click
            flashcards.forEach(card => {
                card.addEventListener('click', (e) => {
                    if (!e.target.classList.contains('favorite-star')) {
                        const answer = card.querySelector('.answer');
                        const explanation = card.querySelector('.explanation');
                        const isVisible = answer.style.display === 'block';
                        answer.style.display = isVisible ? 'none' : 'block';
                        explanation.style.display = isVisible ? 'none' : 'block';
                    }
                });
            });

            // Initial display
            showFlashcard(currentIndex);
        </script>
    <?php endif; ?>
</div>
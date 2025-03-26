<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$courses = $courses ?? [];
$course = $course ?? 'select-course';
$category = $category ?? 'select-category';
$categories = $categories ?? [];
$questions = $questions ?? [];
$submitted = $submitted ?? false;
$score = $score ?? 0;
$user_answers = $user_answers ?? [];
$pageTitle = "Quizzes";
?>
<div class="container my-5">
    <h1 class="display-5 fw-bold text-center mb-5">
        Quiz 
        <?php echo ($course === 'select-course' || $category === 'select-category') 
            ? "Selection" 
            : "for " . htmlspecialchars($course) . " - " . htmlspecialchars($category); ?>
    </h1>

    <!-- Course Menu -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <form method="get" class="mb-3">
                <input type="hidden" name="action" value="quizzes">
                <label for="course-select" class="form-label fw-bold">Select a Course</label>
                <select id="course-select" name="course" class="form-select" onchange="this.form.submit()">
                    <option value="select-course" <?php echo $course === 'select-course' ? 'selected' : ''; ?>>Choose a Course</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo htmlspecialchars($c['course']); ?>" <?php echo $course === $c['course'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['course']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>

    <!-- Category Menu -->
    <?php if ($course !== 'select-course'): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-md-6">
                <form method="get" class="mb-3">
                    <input type="hidden" name="action" value="quizzes">
                    <input type="hidden" name="course" value="<?php echo htmlspecialchars($course); ?>">
                    <label for="category-select" class="form-label fw-bold">Select a Category</label>
                    <select id="category-select" name="category" class="form-select" onchange="this.form.submit()">
                        <option value="select-category" <?php echo $category === 'select-category' ? 'selected' : ''; ?>>Choose a Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category === $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quiz Content -->
    <?php if ($course !== 'select-course' && $category !== 'select-category'): ?>
        <?php if (empty($questions)): ?>
            <div class="alert alert-info text-center">
                No multiple-choice questions available for this category.
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=add flashcard" class="alert-link">Add some now!</a>
                <?php endif; ?>
            </div>
        <?php elseif ($submitted): ?>
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="card shadow-sm bg-light">
                        <div class="card-body text-center">
                            <h2 class="display-6 fw-bold mb-3">Your Score: <?php echo $score; ?>/<?php echo count($questions); ?></h2>
                            <p class="text-muted">Percentage: <?php echo number_format(($score / count($questions)) * 100, 1); ?>%</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo "Question " . ($index + 1) . ": " . htmlspecialchars($q['question']); ?></h5>
                                <p class="card-text">
                                    <strong>Your Answer:</strong> <?php echo htmlspecialchars($user_answers[$q['id']] ?? 'Not answered'); ?>
                                    <?php echo $user_answers[$q['id']] === $q['answer'] 
                                        ? '<span class="badge bg-success ms-2">Correct</span>' 
                                        : '<span class="badge bg-danger ms-2">Incorrect</span>'; ?>
                                </p>
                                <p class="card-text"><strong>Correct Answer:</strong> <?php echo htmlspecialchars($q['answer']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-4">
                        <a href="index.php?action=quizzes&course=<?php echo urlencode($course); ?>&category=<?php echo urlencode($category); ?>" class="btn btn-primary btn-lg">Take Quiz Again</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row justify-content-center mb-4">
                <div class="col-md-10">
                    <div class="progress" role="progressbar" aria-label="Quiz Progress" aria-valuemin="0" aria-valuemax="<?php echo count($questions); ?>">
                        <div id="progress-bar" class="progress-bar" style="width: 0%;">0%</div>
                    </div>
                </div>
            </div>
            <form method="post" class="row justify-content-center">
                <div class="col-md-10">
                    <?php foreach ($questions as $index => $q): ?>
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo "Question " . ($index + 1) . ": " . htmlspecialchars($q['question']); ?></h5>
                                <div class="mt-3">
                                    <?php foreach ($q['options'] as $option): ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input quiz-option" type="radio" name="<?php echo $q['id']; ?>" value="<?php echo htmlspecialchars($option); ?>" id="option_<?php echo $q['id'] . '_' . htmlspecialchars($option); ?>" required>
                                            <label class="form-check-label" for="option_<?php echo $q['id'] . '_' . htmlspecialchars($option); ?>">
                                                <?php echo htmlspecialchars($option); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Submit Quiz</button>
                    </div>
                </div>
            </form>
            <script>
                const totalQuestions = <?php echo count($questions); ?>;
                const progressBar = document.getElementById('progress-bar');
                const options = document.querySelectorAll('.quiz-option');

                function updateProgress() {
                    const answered = document.querySelectorAll('.quiz-option:checked').length;
                    const percentage = totalQuestions > 0 ? (answered / totalQuestions) * 100 : 0;
                    progressBar.style.width = `${percentage}%`;
                    progressBar.textContent = `${Math.round(percentage)}%`;
                }

                options.forEach(option => {
                    option.addEventListener('change', updateProgress);
                });

                updateProgress();
            </script>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$featured_flashcards = $featured_flashcards ?? [];
$user_favorites = $user_favorites ?? [];
$total_flashcards = count($featured_flashcards);
?>
<div class="container my-5">
    <h2 class="display-5 fw-bold text-center mb-4">Welcome to CET2025+ Flashcards</h2>
    <p class="text-center text-muted mb-5 lead">Explore our featured flashcards below!</p>

    <?php if (empty($featured_flashcards)): ?>
        <div class="alert alert-info text-center">
            No featured flashcards available yet.
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="index.php?action=admin" class="alert-link"> Add some in the Admin panel!</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Featured Flashcards Carousel -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div id="featuredCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($featured_flashcards as $index => $flashcard): ?>
                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="card shadow-sm position-relative">
                                    <div class="card-body">
                                        <!-- Favorite Star -->
                                        <?php if (isset($_SESSION['user_id'])): ?>
                                            <a href="index.php?action=toggle_favorite&question_id=<?= $flashcard['id'] ?>&from=home" class="position-absolute top-0 end-0 m-2 text-warning fs-4 text-decoration-none">
                                                <?= in_array($flashcard['id'], $user_favorites) ? '★' : '☆' ?>
                                            </a>
                                        <?php endif; ?>
                                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($flashcard['course']); ?> - <?php echo htmlspecialchars($flashcard['category']); ?></h5>
                                        <p class="card-text"><strong>Question:</strong> <?php echo htmlspecialchars($flashcard['question']); ?></p>
                                        <?php if (!empty($flashcard['image'])): ?>
                                            <img src="img/<?php echo htmlspecialchars($flashcard['image']); ?>" alt="Flashcard image" class="img-fluid rounded mb-3">
                                        <?php endif; ?>
                                        <?php if (!empty($flashcard['options'])): ?>
                                            <div class="options mb-3">
                                                <p><strong>Options:</strong></p>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($flashcard['options'] as $option): ?>
                                                        <li class="list-group-item"><?php echo htmlspecialchars($option); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                        <div class="answer collapse">
                                            <?php if ($flashcard['question_type'] === 'Match' && is_array($flashcard['answer'])): ?>
                                                <p><strong>Answers:</strong></p>
                                                <ul class="list-group list-group-flush">
                                                    <?php foreach ($flashcard['answer'] as $ans): ?>
                                                        <li class="list-group-item"><?php echo htmlspecialchars($ans); ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p><strong>Answer:</strong> <?php echo htmlspecialchars($flashcard['answer']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="explanation collapse">
                                            <p><strong>Explanation:</strong> <?php echo htmlspecialchars($flashcard['explanation'] ?? "No explanation available."); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#featuredCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#featuredCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center mt-4">
            <a href="index.php?action=flashcards" class="btn btn-primary btn-lg">View All Flashcards</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=add flashcard" class="btn btn-success btn-lg ms-3">Create Your Own</a>
            <?php endif; ?>
        </div>

        <script>
            // Toggle answer and explanation on click
            document.querySelectorAll('.carousel-item .card').forEach(card => {
                card.addEventListener('click', (e) => {
                    if (!e.target.closest('a, button')) { // Avoid triggering on links/buttons
                        const answer = card.querySelector('.answer');
                        const explanation = card.querySelector('.explanation');
                        answer.classList.toggle('show');
                        explanation.classList.toggle('show');
                    }
                });
            });
        </script>
    <?php endif; ?>
</div>
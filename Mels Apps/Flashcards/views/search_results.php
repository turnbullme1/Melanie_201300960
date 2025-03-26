<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$results = $results ?? []; // Search results from controller
$query = $query ?? ''; // Search term from GET
$user_favorites = $user_favorites ?? []; // Favorite question IDs
?>
<div class="container my-5">
    <h1 class="display-5 fw-bold text-center mb-4">Search Results</h1>
    <p class="text-center text-muted mb-5 lead">Results for "<?php echo htmlspecialchars($query); ?>"</p>

    <?php if (empty($results)): ?>
        <div class="alert alert-info text-center">
            No flashcards found matching "<?php echo htmlspecialchars($query); ?>".
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?action=add flashcard" class="alert-link"> Add one now!</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <?php foreach ($results as $flashcard): ?>
                    <div class="card shadow-sm mb-4 position-relative">
                        <div class="card-body">
                            <!-- Favorite Star -->
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="index.php?action=toggle_favorite&question_id=<?= $flashcard['id'] ?>&from=search&query=<?= urlencode($query) ?>" class="position-absolute top-0 end-0 m-2 text-warning fs-4 text-decoration-none">
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
                            <button class="btn btn-outline-primary mt-2 toggle-details" data-bs-toggle="collapse" data-bs-target="#flashcard-<?= $flashcard['id'] ?>-details">Toggle Details</button>
                        </div>
                        <div id="flashcard-<?= $flashcard['id'] ?>-details" class="collapse">
                            <div class="card-body border-top">
                                <div class="answer"><?php echo $flashcard['question_type'] === 'Match' && is_array($flashcard['answer']) ? '<p><strong>Answers:</strong></p><ul class="list-group list-group-flush">' . implode('', array_map(fn($ans) => "<li class=\"list-group-item\">" . htmlspecialchars($ans) . "</li>", $flashcard['answer'])) . '</ul>' : '<p><strong>Answer:</strong> ' . htmlspecialchars($flashcard['answer']) . '</p>'; ?></div>
                                <div class="explanation"><p><strong>Explanation:</strong> <?php echo htmlspecialchars($flashcard['explanation'] ?? "No explanation available."); ?></p></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="index.php?action=flashcards" class="btn btn-primary btn-lg">View All Flashcards</a>
        </div>
    <?php endif; ?>
</div>
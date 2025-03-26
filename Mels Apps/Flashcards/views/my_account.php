<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}

$pageTitle = "My Account";
$my_flashcards = $my_flashcards ?? [];
$missed_questions = $missed_questions ?? [];
$favorite_flashcards = $favorite_flashcards ?? [];
$study_index = max(0, min((int)($_GET['study_index'] ?? 0), count($missed_questions) - 1));
$favorite_index = max(0, min((int)($_GET['favorite_index'] ?? 0), count($favorite_flashcards) - 1));
$flashcard_index = max(0, min((int)($_GET['flashcard_index'] ?? 0), count($my_flashcards) - 1));
$total_study = count($missed_questions);
$total_favorites = count($favorite_flashcards);
$total_flashcards = count($my_flashcards);
$open_section = $_GET['open'] ?? 'flashcards'; // Default to 'flashcards'
$message = $message ?? '';
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">My Account</h1>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- My Flashcards -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;" data-section="flashcards">My Flashcards</h2>
        <div class="toggle-content" style="display: <?= $open_section === 'flashcards' ? 'block' : 'none' ?>;">
            <?php if (empty($my_flashcards)): ?>
                <p>You haven’t created any flashcards yet.</p>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card flashcard" id="flashcard-<?= $flashcard_index ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($my_flashcards[$flashcard_index]['question']) ?></h5>
                                <p class="card-text"><small class="text-muted">Course: <?= htmlspecialchars($my_flashcards[$flashcard_index]['course']) ?> | Category: <?= htmlspecialchars($my_flashcards[$flashcard_index]['category']) ?></small></p>
                                <a href="index.php?action=delete_flashcard&question_id=<?= $my_flashcards[$flashcard_index]['id'] ?>&flashcard_index=<?= $flashcard_index ?>&open=flashcards" class="btn btn-danger btn-sm mt-2">Delete Flashcard</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="index.php?action=my_account&flashcard_index=<?= $flashcard_index - 1 ?>&open=flashcards" class="btn btn-secondary <?= $flashcard_index <= 0 ? 'disabled' : '' ?>">Previous</a>
                            <span><?= ($flashcard_index + 1) . ' of ' . $total_flashcards ?></span>
                            <a href="index.php?action=my_account&flashcard_index=<?= $flashcard_index + 1 ?>&open=flashcards" class="btn btn-secondary <?= $flashcard_index >= $total_flashcards - 1 ? 'disabled' : '' ?>">Next</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Missed Questions -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;" data-section="study">Missed Questions</h2>
        <div class="toggle-content" style="display: <?= $open_section === 'study' ? 'block' : 'none' ?>;">
            <?php if (empty($missed_questions)): ?>
                <p>You haven’t missed any questions yet.</p>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($missed_questions[$study_index]['question']) ?></h5>
                                <p class="card-text"><strong>Answer:</strong> <?= htmlspecialchars($missed_questions[$study_index]['answer']) ?></p>
                                <p class="card-text"><small class="text-muted">Course: <?= htmlspecialchars($missed_questions[$study_index]['course']) ?> | Category: <?= htmlspecialchars($missed_questions[$study_index]['category']) ?></small></p>
                                <?php if ($missed_questions[$study_index]['explanation']): ?>
                                    <p class="card-text"><small><strong>Explanation:</strong> <?= htmlspecialchars($missed_questions[$study_index]['explanation']) ?></small></p>
                                <?php endif; ?>
                                <a href="index.php?action=remove_missed&question_id=<?= $missed_questions[$study_index]['id'] ?>&study_index=<?= $study_index ?>&open=study" class="btn btn-danger btn-sm mt-2">Remove from Missed</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="index.php?action=my_account&study_index=<?= $study_index - 1 ?>&open=study" class="btn btn-secondary <?= $study_index <= 0 ? 'disabled' : '' ?>">Previous</a>
                            <span><?= ($study_index + 1) . ' of ' . $total_study ?></span>
                            <a href="index.php?action=my_account&study_index=<?= $study_index + 1 ?>&open=study" class="btn btn-secondary <?= $study_index >= $total_study - 1 ? 'disabled' : '' ?>">Next</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Favorite Flashcards -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;" data-section="favorites">Favorite Flashcards</h2>
        <div class="toggle-content" style="display: <?= $open_section === 'favorites' ? 'block' : 'none' ?>;">
            <?php if (empty($favorite_flashcards)): ?>
                <p>You haven’t favorited any flashcards yet.</p>
            <?php else: ?>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($favorite_flashcards[$favorite_index]['question']) ?></h5>
                                <p class="card-text"><strong>Answer:</strong> <?= htmlspecialchars($favorite_flashcards[$favorite_index]['answer']) ?></p>
                                <p class="card-text"><small class="text-muted">Course: <?= htmlspecialchars($favorite_flashcards[$favorite_index]['course']) ?> | Category: <?= htmlspecialchars($favorite_flashcards[$favorite_index]['category']) ?></small></p>
                                <?php if ($favorite_flashcards[$favorite_index]['explanation']): ?>
                                    <p class="card-text"><small><strong>Explanation:</strong> <?= htmlspecialchars($favorite_flashcards[$favorite_index]['explanation']) ?></small></p>
                                <?php endif; ?>
                                <a href="index.php?action=remove_favorite&question_id=<?= $favorite_flashcards[$favorite_index]['id'] ?>&favorite_index=<?= $favorite_index ?>&open=favorites" class="btn btn-danger btn-sm mt-2">Remove from Favorites</a>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="index.php?action=my_account&favorite_index=<?= $favorite_index - 1 ?>&open=favorites" class="btn btn-secondary <?= $favorite_index <= 0 ? 'disabled' : '' ?>">Previous</a>
                            <span><?= ($favorite_index + 1) . ' of ' . $total_favorites ?></span>
                            <a href="index.php?action=my_account&favorite_index=<?= $favorite_index + 1 ?>&open=favorites" class="btn btn-secondary <?= $favorite_index >= $total_favorites - 1 ? 'disabled' : '' ?>">Next</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Update Account -->
    <div class="mb-4">
        <h2 class="h4 toggle-header" style="cursor: pointer;" data-section="update">Update Account</h2>
        <div class="toggle-content" style="display: <?= $open_section === 'update' ? 'block' : 'none' ?>;">
            <form method="post" action="index.php?action=update_account">
                <div class="mb-3">
                    <label for="new_username" class="form-label">New Username</label>
                    <input type="text" class="form-control" id="new_username" name="new_username" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>
                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Account</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', () => {
            const section = header.getAttribute('data-section');
            window.location.href = `index.php?action=my_account&open=${section}&study_index=<?php echo $study_index; ?>&favorite_index=<?php echo $favorite_index; ?>&flashcard_index=<?php echo $flashcard_index; ?>`;
        });
    });
</script>
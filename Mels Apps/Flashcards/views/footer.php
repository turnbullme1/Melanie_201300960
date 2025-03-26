    </main>
    <footer class="bg-dark text-light py-4 mt-auto">
        <div class="container">
            <div class="row">
                <!-- Navigation Links -->
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold">Explore</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?action=home" class="text-light text-decoration-none">Home</a></li>
                        <li><a href="index.php?action=quizzes" class="text-light text-decoration-none">Quizzes</a></li>
                        <li><a href="index.php?action=flashcards" class="text-light text-decoration-none">Flashcards</a></li>
                        <li><a href="index.php?action=code snippets" class="text-light text-decoration-none">Code Snippets</a></li>
                    </ul>
                </div>
                <!-- Dynamic Stats -->
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold">Stats</h5>
                    <p class="mb-1">Flashcards: <?php echo htmlspecialchars($total_flashcards ?? 'N/A'); ?></p>
                    <p class="mb-1">Users: <?php echo htmlspecialchars($total_users ?? 'N/A'); ?></p>
                    <p class="mb-1">Categories: <?php echo htmlspecialchars($total_categories ?? 'N/A'); ?></p>
                </div>
                <!-- Newsletter Signup -->
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold">Stay Updated</h5>
                    <form method="post" action="index.php?action=subscribe" class="d-flex flex-column gap-2">
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                        <button type="submit" class="btn btn-primary">Subscribe</button>
                    </form>
                <!-- Social Media Links -->
                </div>
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold">Connect</h5>
                    <div class="d-flex gap-3">
                        <a href="https://twitter.com" class="text-light" target="_blank" aria-label="Twitter"><i class="bi bi-twitter fs-4"></i></a>
                        <a href="https://github.com" class="text-light" target="_blank" aria-label="GitHub"><i class="bi bi-github fs-4"></i></a>
                        <a href="https://linkedin.com" class="text-light" target="_blank" aria-label="LinkedIn"><i class="bi bi-linkedin fs-4"></i></a>
                    </div>
                </div>
            </div>
            <!-- Admin Quick Links (if admin) -->
            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <h5 class="fw-bold">Admin</h5>
                        <ul class="list-unstyled">
                            <li><a href="index.php?action=admin" class="text-light text-decoration-none">Admin Panel</a></li>
                            <li><a href="index.php?action=add flashcard" class="text-light text-decoration-none">Add Flashcard</a></li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <!-- Dynamic Footer Message and Copyright -->
            <div class="text-center mt-3 border-top pt-3">
                <?php if (isset($footer_message) && $footer_message): ?>
                    <p class="mb-1"><?php echo htmlspecialchars($footer_message); ?></p>
                <?php endif; ?>
                <p class="mb-0">© <?php echo date('Y'); ?> CET2025+ Flashcard Learning Platform. All rights reserved.</p>
            </div>
        </div>
        <!-- Back-to-Top Button -->
        <button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-3" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Back to top">
            <i class="bi bi-arrow-up"></i>
        </button>
    </footer>
</html>
</body>
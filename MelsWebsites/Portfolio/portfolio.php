<?php
$page_title = "Portfolio - Melanie's Portfolio";
$current_page = "portfolio";
require_once 'includes/header.php';
?>

<section id="portfolio" class="elegant-section">
    <h2>My Portfolio</h2>
    <p class="section-subtitle">A showcase of my works in web development and design</p>

    <div class="portfolio-grid">
        <div class="portfolio-item">
            <h3>MeezersCreations</h3>
            <p>A creative endeavor featuring distinctive designs and seamless functionality.</p>
            <a href="../MeezersCreations/" class="project-link">Explore Project</a>
        </div>

        <div class="portfolio-item">
            <h3>Zephys Forest</h3>
            <p>A tranquil, nature-inspired project with elegant design and intuitive navigation.</p>
            <a href="../Forest/index.php" class="project-link">Explore Project</a>
        </div>

        <div class="portfolio-item">
            <h3>Flashcards</h3>
            <p>An sophisticated educational tool for mastering concepts through interactive quizzes.</p>
            <a href="../Flashcards/" class="project-link">Explore Project</a>
        </div>

    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
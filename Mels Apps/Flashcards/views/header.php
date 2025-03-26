<?php
$pageTitle = isset($pageTitle) ? $pageTitle : "Flashcards for and by Computer Engineering Technician Students"; // Default title
$banner_message = isset($banner_message) ? $banner_message : ''; // Default to empty string, should come from DB
$unread_count = isset($unread_count) ? $unread_count : 0; // Example: unread notifications, from DB
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "CET Studying - " . htmlspecialchars($pageTitle); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Bootstrap Icons (for bell icon) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Optional Custom CSS for Marquee -->
    <link rel="stylesheet" href="views/assets/css/custom.css">
    <!-- Bootstrap JS (deferred) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <header>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="navbar-brand fw-bold" href="index.php?action=home">
                    <span class="d-none d-lg-inline">CET2025+</span>
                    <span class="d-lg-none">CET</span>
                </a>
                <!-- Toggler -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar Content -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=home">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=quizzes">Quizzes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=flashcards">Flashcards</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?action=code snippets">Code Snippets</a>
                        </li>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin']): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=admin">Admin</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <!-- Search Bar -->
                    <form class="d-flex ms-auto me-3" method="get" action="index.php">
                        <input type="hidden" name="action" value="search">
                        <input class="form-control me-2" type="search" name="query" placeholder="Search Flashcards" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                    <!-- Right Side Navbar -->
                    <ul class="navbar-nav mb-2 mb-lg-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Notifications -->
                            <li class="nav-item">
                                <a class="nav-link position-relative" href="index.php?action=notifications">
                                    <i class="bi bi-bell"></i>
                                    <?php if ($unread_count > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?php echo $unread_count; ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <!-- User Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="index.php?action=my_account">My Account</a></li>
                                    <li><a class="dropdown-item" href="index.php?action=logout">Logout</a></li>
                                </ul>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?action=login">Login</a>
                            </li>
                        <?php endif; ?>
                        <!-- Theme Toggle -->
                        <li class="nav-item">
                            <button class="btn btn-outline-light ms-2" id="theme-toggle" aria-label="Toggle theme">
                                <i class="bi bi-moon-stars"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Title -->
        <div class="bg-light py-3 text-center">
            <h1 class="display-6 fw-bold text-dark"><?php echo htmlspecialchars($pageTitle); ?></h1>
        </div>

        <!-- Scrolling Banner -->
        <?php if ($banner_message): ?>
            <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                <div class="marquee">
                    <span><?php echo htmlspecialchars($banner_message); ?></span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </header>
    <main class="flex-grow-1">
	
	<script>
    document.getElementById('theme-toggle').addEventListener('click', () => {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        html.setAttribute('data-bs-theme', newTheme);
        const icon = document.querySelector('#theme-toggle i');
        icon.className = newTheme === 'light' ? 'bi bi-moon-stars' : 'bi bi-sun';
        // Optional: Save preference to localStorage or send to server via AJAX
        localStorage.setItem('theme', newTheme);
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        const icon = document.querySelector('#theme-toggle i');
        icon.className = savedTheme === 'light' ? 'bi bi-moon-stars' : 'bi bi-sun';
    }
</script>
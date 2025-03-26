<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Melanie\'s Portfolio'; ?></title>
    <link rel="stylesheet" href="indexcss/styles.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <img src="images/your-photo.jpg" alt="Melanie Turnbull" class="profile-img">
            <h1>Melanie Turnbull</h1>
            <p class="tagline">Web Developer | Designer | Creative Professional</p>
        </div>
        <nav class="horizontal-nav">
            <ul>
                <li><a href="index.php" <?php if($current_page == 'home') echo 'class="active"'; ?>>Home</a></li>
                <li><a href="about.php" <?php if($current_page == 'about') echo 'class="active"'; ?>>About</a></li>
                <li><a href="portfolio.php" <?php if($current_page == 'portfolio') echo 'class="active"'; ?>>Portfolio</a></li>
                <li><a href="contact.php" <?php if($current_page == 'contact') echo 'class="active"'; ?>>Contact</a></li>
            </ul>
        </nav>

        <div class="mobile-menu">
            <div class="hamburger">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
        </div>
        <nav class="mobile-nav">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="portfolio.php">Portfolio</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main class="main-content">
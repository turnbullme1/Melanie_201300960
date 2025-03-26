<?php $servername = "turnbulls.info";
$username = "turnbull_user";
$password = "P0t@t03s!";
$dbname = "turnbull_flashcards_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<header>
    <div class="header-top">
        <img src="images/Logos/forest.jpg" alt="Zephy's Forest Logo">
        <h1>Welcome to Zephy's Forest   </h1>
        <div class="hamburger"> ☰</div>
		    <link rel="stylesheet" href="styles.css">
    </div>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="tarot_game.php">Tarot Game</a></li>
            <li><a href="forum.php">Forum</a></li>
            <li><a href="login.php">Login/Register</a></li>
        </ul>
    </nav>
</header>
<script>
    document.querySelector('.hamburger').addEventListener('click', function() {
        document.querySelector('nav').classList.toggle('show');
    });
</script>
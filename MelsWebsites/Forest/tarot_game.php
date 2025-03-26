<?php
// Include header
include 'includes/header.php';

// Database connection
$servername = "turnbulls.info";
$username = "turnbull_user";
$password = "P0t@t03s!";
$dbname = "turnbull_flashcards_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Handle form submission
$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
$topic = isset($_POST['topic']) ? htmlspecialchars($_POST['topic']) : '';
$readingType = isset($_POST['reading_type']) ? htmlspecialchars($_POST['reading_type']) : '';

// Function to randomly select cards
function getRandomCards($pdo, $limit) {
    $sql = "SELECT name FROM tarot_cards ORDER BY RAND() LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch card details
function getCardDetails($pdo, $cardName, $reversed) {
    $sql = "SELECT name, meaning, reversed_meaning FROM tarot_cards WHERE name = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $cardName, PDO::PARAM_STR);
    $stmt->execute();
    $card = $stmt->fetch(PDO::FETCH_ASSOC);

    $basePath = 'images/' . strtolower(str_replace(' ', '_', $cardName));
    $imagePath = file_exists("{$basePath}.png") ? "{$basePath}.png" : (file_exists("{$basePath}.jpg") ? "{$basePath}.jpg" : 'images/default.png');
    $reversedImagePath = file_exists("{$basePath}_reversed.png") ? "{$basePath}_reversed.png" : (file_exists("{$basePath}_reversed.jpg") ? "{$basePath}_reversed.jpg" : $imagePath);
    
    return [
        'name' => $card['name'],
        'meaning' => $reversed ? $card['reversed_meaning'] : $card['meaning'],
        'orientation' => $reversed ? 'Reversed' : 'Upright',
        'image' => $reversed ? $reversedImagePath : $imagePath
    ];
}

// Determine number of cards based on reading type
$cardsLimit = $readingType === 'daily' ? 1 : ($readingType === 'destinys_stallion' ? 7 : ($readingType === 'guiding_star' ? 6 : 3));
$cards = getRandomCards($pdo, $cardsLimit);
?>
<body>
    <div class="container">
        <h2>Tarot Reading</h2>
        <?php if (!$name): ?>
            <form action="" method="POST">
                <label>Your Name: <input type="text" name="name" required></label>
                <label>Topic:
                    <select name="topic" required>
                        <option value="general">General</option>
                        <option value="career">Career</option>
                        <option value="finance">Finance</option>
                        <option value="love">Love</option>
                    </select>
                </label>
                <label>Reading Type:
                    <select name="reading_type" required>
                        <option value="past_present_future">Past, Present, Future</option>
                        <option value="daily">Daily</option>
                        <option value="destinys_stallion">Destiny's Stallion</option>
                        <option value="guiding_star">Guiding Star</option>
                    </select>
                </label>
                <button type="submit">Get My Reading</button>
            </form>
        <?php else: ?>
            <h3><?php echo ucfirst($topic); ?> Reading - <?php echo ucfirst(str_replace('_', ' ', $readingType)); ?></h3>
            <div class="card-layout">
                <?php foreach ($cards as $index => $card): ?>
                    <?php $reversed = rand(0, 1) == 1; $cardDetails = getCardDetails($pdo, $card['name'], $reversed); ?>
                    <div class="card">
                        <img id="card-<?php echo $index; ?>" src="images/cardback.png" alt="Card Back" onclick="revealCard(<?php echo $index; ?>, '<?php echo $cardDetails['image']; ?>', '<?php echo $cardDetails['name']; ?>', <?php echo $reversed ? 'true' : 'false'; ?>)">
                        <div class="card-details" id="details-<?php echo $index; ?>">
                            <h3 id="card-name-<?php echo $index; ?>" style="display: none;"> <?php echo $cardDetails['name']; ?> </h3>
                            <p><strong>Orientation:</strong> <?php echo $cardDetails['orientation']; ?></p>
                            <p><strong>Meaning:</strong> <?php echo $cardDetails['meaning']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form action="" method="POST">
                <button type="submit">New Reading</button>
            </form>
        <?php endif; ?>

    </div>

    <script>
        function revealCard(index, imagePath, cardName, reversed) {
            var cardImg = document.getElementById("card-" + index);
            var details = document.getElementById("details-" + index);
            var cardNameElement = document.getElementById("card-name-" + index);
            cardImg.src = imagePath;
            details.style.display = 'block';
            cardNameElement.style.display = 'block';
            
            if (reversed) {
                cardImg.classList.add("reversed");
            }
        }
    </script>

<?php include 'includes/footer.php'; ?>

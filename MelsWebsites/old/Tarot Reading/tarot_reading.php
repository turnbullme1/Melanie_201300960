<?php
// Database connection
$host = 'localhost';
$dbname = 'tarot';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

function drawCards($readingType) {
    switch($readingType) {
        case "Daily":
            return 1;
        case "Past Present Future":
            return 3;
        case "Destinys Stallion":
            return 7;
        case "Guiding Star":
            return 6;
        default:
            return null;
    }
}

function getCardAttributes($pdo, $cardName, $reversed) {
    if ($reversed) {
        $stmt = $pdo->prepare("SELECT reversed_meaning, yes_no FROM tarot_cards WHERE name = :name");
    } else {
        $stmt = $pdo->prepare("SELECT upright_meaning, yes_no FROM tarot_cards WHERE name = :name");
    }
    $stmt->execute(['name' => $cardName]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row : 'Unknown card';
}

function getSuitFeatures($cardName) {
    if (strpos($cardName, 'Wands') !== false) {
        return "Suit of Wands: Inspiration, determination, strength, intuition.";
    } elseif (strpos($cardName, 'Cups') !== false) {
        return "Suit of Cups: Emotions, relationships, connections, feelings.";
    } elseif (strpos($cardName, 'Swords') !== false) {
        return "Suit of Swords: Intellect, decisions, clarity, conflict.";
    } elseif (strpos($cardName, 'Pentacles') !== false) {
        return "Suit of Pentacles: Material aspects, health, wealth, career.";
    } else {
        return "Major Arcana: Life lessons, significant events, personal growth.";
    }
}

$action = filter_input(INPUT_POST, 'action');

if ($action == "Next" || $action == "Submit Cards") {
    $situation = filter_input(INPUT_POST, 'situation', FILTER_SANITIZE_STRING);
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $topic = filter_input(INPUT_POST, 'topic', FILTER_SANITIZE_STRING);
    $readingType = filter_input(INPUT_POST, 'readingType', FILTER_SANITIZE_STRING);

    $message = "";

    if (empty($situation) || empty($name) || empty($readingType)) {
        $message = "<p class='error'>Please complete all required fields</p>";
    }

    if (empty($message)) {
        $n = drawCards($readingType);
        if ($n == NULL) {
            $message = "<p class='error'>Invalid reading type.</p>";
        } else {
            $message = "<div class='details'>
                        <label>Question or Situation:</label>
                        <span>$situation</span><br>
                        <label>Subjects Name:</label>
                        <span>$name</span><br>
                        <label>Topic:</label>
                        <span>$topic</span><br>
                        <label>Reading Type:</label>
                        <span>$readingType</span><br>
                        </div>";

            if ($action == "Next") {
                $message .= "<form action='tarot_reading.php' method='post' id='tarotForm'>
                                <input type='hidden' name='situation' value='$situation'>
                                <input type='hidden' name='name' value='$name'>
                                <input type='hidden' name='topic' value='$topic'>
                                <input type='hidden' name='readingType' value='$readingType'>
                                <div class='cards'>";

                for ($i = 1; $i <= $n; $i++) {
                    $message .= "<label>Card $i:</label>
                                 <input type='text' name='card$i' id='card$i'>
                                 <button type='button' onclick='getRandomCard($i)'>Random Card</button><br>";
                }
                $message .= "</div><input type='submit' name='action' value='Submit Cards'><br></form>";
            } else {
                $results = "<div class='results'>";
                $results_text = "Tarot Reading Results\n";
                $results_text .= "Question or Situation: $situation\n";
                $results_text .= "Subjects Name: $name\n";
                $results_text .= "Topic: $topic\n";
                $results_text .= "Reading Type: $readingType\n";

                for ($i = 1; $i <= $n; $i++) {
                    $card = filter_input(INPUT_POST, "card$i", FILTER_SANITIZE_STRING);
                    $reversed = strpos($card, ' (Reversed)') !== false;
                    $cardName = $reversed ? str_replace(' (Reversed)', '', $card) : $card;
                    $attributes = getCardAttributes($pdo, $cardName, $reversed);
                    $suitFeatures = getSuitFeatures($cardName);
                    $uprightOrReversed = $reversed ? 'Reversed' : 'Upright';

                    $cardImage = strtolower(str_replace(' ', '_', $cardName)); // Generate card image path
                    $cardImagePath = $reversed ? "images/cards/{$cardImage}_reversed.jpg" : "images/cards/$cardImage.jpg";

                    $results .= "<label><b>Card $i:</b></label><span><b><i>$card</b></i></span><br>
                                 <img src='images/cardback.jpg' alt='Card Back' class='card-image'>
                                 <img src='$cardImagePath' alt='$cardName' class='card-image'><br>
                                 <span>Attribute: " . ($reversed ? $attributes['reversed_meaning'] : $attributes['upright_meaning']) . "</span><br>
                                 <span>Yes/No: {$attributes['yes_no']}</span><br>
                                 <span>$suitFeatures</span><br>";
                    $results_text .= "Card $i: $card ($uprightOrReversed)\n";
                    $results_text .= "Attribute: " . ($reversed ? $attributes['reversed_meaning'] : $attributes['upright_meaning']) . "\n";
                    $results_text .= "Yes/No: {$attributes['yes_no']}\n";
                }
                $results .= "</div>";
                $message .= $results;

                $message .= "<button onclick='downloadResults()'>Download Results</button>";

                echo "<script>
                function downloadResults() {
                    var blob = new Blob(['" . str_replace("\n", "\\n", addslashes($results_text)) . "'], { type: 'text/plain' });
                    var link = document.createElement('a');
                    link.href = URL.createObjectURL(blob);
                    link.download = 'tarot_reading_results.txt';
                    link.click();
                }
                </script>";
            }
        }
    } else {
        $message = "<div class='error'>$message</div><p><a href='tarot_reading.php'>Back to Application Form</a></p>";
    }

    ?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Tarot Reading</title>
        <link rel="stylesheet" href="main.css">
        <script>
            function getRandomCard(cardNum) {
                fetch('random_card.php')
                .then(response => response.text())
                .then(cardName => {
                    document.getElementById('card' + cardNum).value = cardName;
                });
            }
        </script>
    </head>
    <body>
        <main>
            <h1>Tarot Reading</h1>
            <?php echo ($message); ?>
        </main>
    </body>
    </html>

<?php
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tarot Reading</title>
    <link rel="stylesheet" href="main.css">
</head>
<body>
    <main>
    <h1>Tarot Reading</h1>        
    <form action="tarot_reading.php" method="post" id="tarotForm">
        <div id="data">
            <label for="situation">Question or Situation: </label>
            <textarea name="situation" id="comments"></textarea><br>
            <label>Subjects Name:</label>
            <input type="text" name="name"><br>
            <label>Topic:</label><br>
            <input type="radio" name="topic" id="general" value="General" checked>
            <label for="general"> General / Life</label><br>
            <input type="radio" name="topic" id="romance" value="Romance">
            <label for="romance"> Romance </label><br>
            <input type="radio" name="topic" id="family" value="Family">
            <label for="family"> Family </label><br>
            <input type="radio" name="topic" id="career" value="Career">
            <label for="career"> Career </label><br>
            <label>Reading Type:</label>
            <select name="readingType">
                <option value=""></option>
                <option value="Daily">Daily (1 card)</option>
                <option value="Past Present Future">Past Present Future (3 cards)</option>
                <option value="Destinys Stallion">Destinys Stallion (7 cards)</option>
                <option value="Guiding Star">Guiding Star (6 cards)</option> 
            </select><br>
        </div>
        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" name="action" value="Next"><br>
        </div>
    </form>
    </main>
</body>
</html>
<?php
}
?>  

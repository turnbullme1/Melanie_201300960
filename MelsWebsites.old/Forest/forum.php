<?php
include 'includes/header.php';

// Database connection
$dsn = 'mysql:host=localhost;dbname=forum_db';
$dbuser = 'root';
$dbpass = '';

try {
    $db = new PDO($dsn, $dbuser, $dbpass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Function to add a comment
function addComment($name, $comment, $category_id) {
    global $db;
    $query = 'INSERT INTO comments (author, content, category_id, created_at) 
              VALUES (:name, :comment, :category_id, NOW())';
    $statement = $db->prepare($query);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':comment', $comment);
    $statement->bindValue(':category_id', $category_id);
    try {
        $statement->execute();
    } catch (PDOException $e) {
        echo "Error inserting comment: " . $e->getMessage();
    }
    $statement->closeCursor();
}

// Function to retrieve categories with comments
function getCategoriesWithComments() {
    global $db;
    $query = 'SELECT categories.id AS category_id, categories.name AS category_name, 
                     comments.author, comments.content, comments.created_at 
              FROM categories
              LEFT JOIN comments ON categories.id = comments.category_id
              ORDER BY categories.id, comments.created_at DESC';
    $statement = $db->prepare($query);
    $statement->execute();
    $categories = $statement->fetchAll();
    $statement->closeCursor();
    return $categories;
}

$action = filter_input(INPUT_POST, 'action');
if ($action == "Submit Comment") {
    $name = filter_input(INPUT_POST, 'name');
    $comment = filter_input(INPUT_POST, 'comment');
    $category_id = filter_input(INPUT_POST, 'category');
    addComment($name, $comment, $category_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forum - Zephy's Forest</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>All Categories and Comments:</h2>
        <div class="comments-container">
            <?php
                $rows = getCategoriesWithComments();
                $groupedComments = [];
                foreach ($rows as $row) {
                    $catId = $row['category_id'];
                    if (!isset($groupedComments[$catId])) {
                        $groupedComments[$catId] = [
                            'category_name' => $row['category_name'],
                            'comments' => []
                        ];
                    }
                    if ($row['author']) {
                        $groupedComments[$catId]['comments'][] = [
                            'author' => $row['author'],
                            'content' => $row['content'],
                            'created_at' => $row['created_at']
                        ];
                    }
                }
                foreach ($groupedComments as $catId => $categoryData) {
                    echo "<h3 class='category-title' onclick='toggleComments(" . $catId . ")'>" . $categoryData['category_name'] . "</h3>";
                    echo "<div id='comments-" . $catId . "' class='comments-section' style='display: none;'>";
                    if (empty($categoryData['comments'])) {
                        echo "<p>No comments yet.</p>";
                    } else {
                        foreach ($categoryData['comments'] as $comment) {
                            echo "<p><strong>Author:</strong> " . $comment['author'] . "</p>";
                            echo "<p><strong>Comment:</strong> " . $comment['content'] . "</p>";
                            echo "<p><em>Posted on: " . $comment['created_at'] . "</em></p>";
                            echo "<hr>";
                        }
                    }
                    echo "</div>";
                }
            ?>
        </div>
        <h2>Add a Comment</h2>
        <form action="" method="post">
            <fieldset>
                <legend>Enter Your Comment</legend>
                <label for="name">Name: <input type="text" name="name" id="name" required></label>
                <label for="comment">Comment: <textarea name="comment" id="comment" required></textarea></label>
                <label for="category">Category: 
                    <select name="category" id="category">
                        <?php
                            $query = 'SELECT * FROM categories';
                            $statement = $db->prepare($query);
                            $statement->execute();
                            $categoriesList = $statement->fetchAll();
                            foreach ($categoriesList as $category) {
                                echo "<option value='" . $category['id'] . "'>" . $category['name'] . "</option>";
                            }
                            $statement->closeCursor();
                        ?>
                    </select>
                </label>
                <input type="submit" name="action" value="Submit Comment">
            </fieldset>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>

    <script>
        function toggleComments(categoryId) {
            const commentsSection = document.getElementById("comments-" + categoryId);
            if (commentsSection.style.display === "none" || commentsSection.style.display === "") {
                commentsSection.style.display = "block";
            } else {
                commentsSection.style.display = "none";
            }
        }
    </script>
</body>
</html>
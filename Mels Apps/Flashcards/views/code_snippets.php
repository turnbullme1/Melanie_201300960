<?php
$selected_category = $_GET['category'] ?? null;
$pageTitle = "Code Snippets";
?>

<h1>Code Snippets</h1>
<?php
if ($selected_category && array_key_exists($selected_category, $snippets)) {
    echo "<h2>" . htmlspecialchars($selected_category) . " Snippets</h2>";
    foreach ($snippets[$selected_category] as $subcategory => $snippets_list) {
        echo "<h3>" . htmlspecialchars($subcategory) . "</h3>";
        foreach ($snippets_list as $snippet) {
            echo "<div class='snippet-box'>";
            echo "<h4>" . htmlspecialchars($snippet['title']) . "</h4>";
            echo "<pre><code>" . htmlspecialchars($snippet['code']) . "</code></pre>";
            echo "</div>";
        }
    }
    echo "<p><a href='index.php?action=Code%20Snippets'>Back to categories</a></p>";
} else {
    echo "<h2>Available Categories</h2>";
    echo "<ul class='category-list'>";
    foreach (array_keys($snippets) as $category) {
        echo "<li class='category-item'><a href='index.php?action=Code%20Snippets&category=" . urlencode($category) . "'>" . htmlspecialchars($category) . "</a></li>";
    }
    echo "</ul>";
}
?>
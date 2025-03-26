<?php
// Include the header for consistent site layout
require_once 'includes/header.php';

// Define an array of code snippets organized by category
$snippets = [
    "Java" => [
        "Base Structure" => [
            ["title" => "Class Declaration", "code" => "public class MyClass {\n    // code\n}"],
            ["title" => "Main Method", "code" => "public static void main(String[] args) {\n    // code\n}"]
        ],
        "Loops" => [
            ["title" => "For Loop", "code" => "for(int i = 0; i < 10; i++) {\n    System.out.println(i);\n}"],
            ["title" => "While Loop", "code" => "int i = 0;\nwhile(i < 10) {\n    System.out.println(i);\n    i++;\n}"]
        ],
        "Decision Making" => [
            ["title" => "If-Else Statement", "code" => "if (condition) {\n    // code\n} else {\n    // code\n}"]
        ],
        "Methods" => [
            ["title" => "Method Declaration", "code" => "public void myMethod() {\n    // code\n}"]
        ]
    ],
    "SQL" => [
        "Creation" => [
            ["title" => "Create Table", "code" => "CREATE TABLE users (\n    id INT PRIMARY KEY,\n    name VARCHAR(100)\n);"],
            ["title" => "Create Index", "code" => "CREATE INDEX idx_name ON users(name);"]
        ],
        "Management" => [
            ["title" => "Insert Data", "code" => "INSERT INTO users (id, name) VALUES (1, 'John');"]
        ],
        "Selection" => [
            ["title" => "Select All", "code" => "SELECT * FROM users;"]
        ],
        "Filtering" => [
            ["title" => "Where Clause", "code" => "SELECT * FROM users WHERE id = 1;"]
        ]
    ],
"HTML" => [
        "Base Structure" => [
            [
                "title" => "HTML Document",
                "code" => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Title</title>\n</head>\n<body>\n    <!-- content -->\n</body>\n</html>"
            ]
        ],
        "HTML Styles" => [
            [
                "title" => "Inline Style",
                "code" => "<p style=\"color: red;\">Text</p>"
            ]
        ],
        "HTML in PHP" => [
            [
                "title" => "Echo HTML",
                "code" => "<?php echo '<p>Hello</p>'; ?>"
            ]
        ],
        "HTML and Databases" => [
            [
                "title" => "Display Data",
                "code" => "<table>\n    <?php foreach (\$data as \$row) { ?>\n    <tr><td><?php echo \$row['field']; ?></td></tr>\n    <?php } ?>\n</table>"
            ]
        ]
    ],
    "CSS" => [
        "Base Structure" => [
            ["title" => "CSS Rule", "code" => "selector {\n    property: value;\n}"]
        ],
        "Selectors" => [
            ["title" => "Class Selector", "code" => ".className {\n    color: blue;\n}"]
        ],
        "Layout" => [
            ["title" => "Flexbox", "code" => ".container {\n    display: flex;\n}"]
        ],
        "Responsive Design" => [
            ["title" => "Media Query", "code" => "@media (max-width: 600px) {\n    body {\n        background-color: lightblue;\n    }\n}"]
        ]
    ],
 "PHP" => [
    "Base Structure" => [
        [
            "title" => "PHP Tag",
            "code" => "<?php\n    // code\n?>"
        ]
    ],
    "Functions" => [
        [
            "title" => "Function Definition",
            "code" => "function myFunction() {\n    // code\n}"
        ]
    ],
    "Single Page PHP" => [
        [
            "title" => "Form Handling",
            "code" => "if (\$_SERVER['REQUEST_METHOD'] == 'POST') {\n    // process form\n}"
        ]
    ],
    "PHP and Databases" => [
        [
            "title" => "MySQL Connection",
            "code" => "\$conn = new mysqli('host', 'user', 'pass', 'db');"
        ]
    ]
],
    "Cisco Networking & Security" => [
        "Basic Router Config" => [
            ["title" => "Set Hostname", "code" => "Router(config)# hostname MyRouter"],
            ["title" => "Interface Config", "code" => "Router(config)# interface FastEthernet0/0\nRouter(config-if)# ip address 192.168.1.1 255.255.255.0"]
        ],
        "Basic Switch Config" => [
            ["title" => "Set VLAN", "code" => "Switch(config)# vlan 10"]
        ],
        "Basic Networking Security" => [
            ["title" => "Enable Password", "code" => "Router(config)# enable secret mypassword"]
        ],
        "ACLs" => [
            ["title" => "Standard ACL", "code" => "Router(config)# access-list 1 deny 192.168.1.0 0.0.0.255"]
        ],
        "VLANS" => [
            ["title" => "Assign Port to VLAN", "code" => "Switch(config-if)# switchport access vlan 10"]
        ],
        "Access Levels" => [
            ["title" => "User Mode", "code" => "Router>"]
        ]
    ]
];


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
    echo "<p><a href='code_snippets.php'>Back to categories</a></p>";
} else {
    echo "<h2>Available Categories</h2>";
    echo "<ul class='category-list'>";
    foreach (array_keys($snippets) as $category) {
        echo "<li class='category-item'><a href='code_snippets.php?category=" . urlencode($category) . "'>" . htmlspecialchars($category) . "</a></li>";
    }
    echo "</ul>";
}
?>

<?php require_once 'includes/footer.php'; ?>
<?php
// model/functions.php
require_once 'db_connect.php'; // Assumes $db (PDO) is defined here

function getAllFlashcards() {
    global $db;
    $stmt = $db->query("SELECT * FROM questions ORDER BY course, category");
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getFlashcardsByCourseAndCategory($course, $categories) {
    global $db;
    $flashcards = [];
    if ($course && $categories) {
        if (in_array('all', $categories)) {
            $stmt = $db->prepare("SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE course = ?");
            $stmt->execute([$course]);
        } else {
            $placeholders = implode(',', array_fill(0, count($categories), '?'));
            $query = "SELECT id, question, options, answer, explanation, question_type, image FROM questions WHERE course = ? AND category IN ($placeholders)";
            $stmt = $db->prepare($query);
            $stmt->execute(array_merge([$course], $categories));
        }
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $flashcards[] = [
                'id' => $row['id'],
                'question' => $row['question'],
                'options' => $row['options'] ? json_decode($row['options'], true) : null,
                'answer' => $row['question_type'] === 'Match' ? json_decode($row['answer'], true) : $row['answer'],
                'explanation' => $row['explanation'],
                'question_type' => $row['question_type'],
                'image' => $row['image'] ?? null
            ];
        }
    }
    return $flashcards;
}

function getQuizQuestions($course, $category) {
    global $db;
    $questions = [];
    if ($course && $category) {
        $stmt = $db->prepare("SELECT id, question, options, answer FROM questions WHERE course = ? AND category = ? AND question_type = 'Multiple Choice' ORDER BY RAND() LIMIT 10");
        $stmt->execute([$course, $category]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $questions[] = [
                'id' => $row['id'],
                'question' => $row['question'],
                'options' => json_decode($row['options'], true),
                'answer' => $row['answer']
            ];
        }
    }
    return $questions;
}

function getSetting($key, $db) {
    $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    return $stmt->fetchColumn() ?: '';
}

function addFlashcard($course, $category, $question, $answer, $explanation, $question_type, $options, $db) {
    $stmt = $db->prepare("INSERT INTO questions (course, category, question, answer, explanation, question_type, options) VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$course, $category, $question, $answer, $explanation, $question_type, $options]);
}


function getCourses($db) {
    $stmt = $db->query("SELECT DISTINCT course FROM questions ORDER BY course");
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}



function getFeaturedFlashcards($db) {
    $stmt = $db->query("SELECT * FROM questions WHERE featured = 1");
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getCategories($course, $db) {
    $stmt = $db->prepare("SELECT DISTINCT category FROM questions WHERE course = ?");
    $stmt->execute([$course]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function updateFeaturedFlashcards($course, $categories, $db) {
    // Reset current featured status
    $stmt = $db->prepare("UPDATE questions SET featured = 0 WHERE course = ?"); // Line 88 (example)
    $stmt->execute([$course]);
    // Set new featured flashcards
    if (!empty($categories)) {
        $placeholders = implode(',', array_fill(0, count($categories), '?'));
        $stmt = $db->prepare("UPDATE questions SET featured = 1 WHERE course = ? AND category IN ($placeholders)");
        $stmt->execute(array_merge([$course], $categories));
    }
}

function updateBannerMessage($message, $db) {
    updateBannerMessage($banner_message_update, $db);
$banner_message = $banner_message_update;
}

function getDatabase(): PDO {
    static $db = null;
    if ($db === null) {
        try {
            $db = new PDO("mysql:host=localhost;dbname=your_db_name", "your_username", "your_password", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $db;
}

function renderView(string $view, array $data = []): void {
    extract($data);
    include 'views/header.php';
    include "views/$view.php";
    include 'views/footer.php';
}



function getCodeSnippets() {
return [
    "Java" => [
        "Base Structure" => [
            ["title" => "Class Declaration", "code" => "public class MyClass {\n    // code\n}"],
            ["title" => "Main Method", "code" => "public static void main(String[] args) {\n    // code\n}"],
            ["title" => "Constructor", "code" => "public MyClass() {\n    // initialization code\n}"],
            ["title" => "Instance Variable", "code" => "public class MyClass {\n    int myVar = 10;\n}"]
        ],
        "Loops" => [
            ["title" => "For Loop", "code" => "for(int i = 0; i < 10; i++) {\n    System.out.println(i);\n}"],
            ["title" => "While Loop", "code" => "int i = 0;\nwhile(i < 10) {\n    System.out.println(i);\n    i++;\n}"],
            ["title" => "Do-While Loop", "code" => "int i = 0;\ndo {\n    System.out.println(i);\n    i++;\n} while(i < 10);"],
            ["title" => "Enhanced For Loop", "code" => "int[] numbers = {1, 2, 3};\nfor(int num : numbers) {\n    System.out.println(num);\n}"]
        ],
        "Decision Making" => [
            ["title" => "If-Else Statement", "code" => "if (condition) {\n    // code\n} else {\n    // code\n}"],
            ["title" => "Switch Statement", "code" => "switch (value) {\n    case 1:\n        System.out.println(\"One\");\n        break;\n    default:\n        System.out.println(\"Other\");\n}"],
            ["title" => "Ternary Operator", "code" => "int result = (a > b) ? a : b;"],
            ["title" => "Nested If", "code" => "if (a > 0) {\n    if (b > 0) {\n        System.out.println(\"Both positive\");\n    }\n}"]
        ],
        "Methods" => [
            ["title" => "Method Declaration", "code" => "public void myMethod() {\n    // code\n}"],
            ["title" => "Return Method", "code" => "public int add(int a, int b) {\n    return a + b;\n}"],
            ["title" => "Static Method", "code" => "public static void print() {\n    System.out.println(\"Hello\");\n}"],
            ["title" => "Method with Parameters", "code" => "public void greet(String name) {\n    System.out.println(\"Hello, \" + name);\n}"]
        ]
    ],
    "SQL" => [
        "Creation" => [
            ["title" => "Create Table", "code" => "CREATE TABLE users (\n    id INT PRIMARY KEY,\n    name VARCHAR(100)\n);"],
            ["title" => "Create Index", "code" => "CREATE INDEX idx_name ON users(name);"],
            ["title" => "Create Database", "code" => "CREATE DATABASE mydb;"],
            ["title" => "Create View", "code" => "CREATE VIEW active_users AS\nSELECT * FROM users WHERE active = 1;"]
        ],
        "Management" => [
            ["title" => "Insert Data", "code" => "INSERT INTO users (id, name) VALUES (1, 'John');"],
            ["title" => "Update Data", "code" => "UPDATE users SET name = 'Jane' WHERE id = 1;"],
            ["title" => "Delete Data", "code" => "DELETE FROM users WHERE id = 1;"],
            ["title" => "Alter Table", "code" => "ALTER TABLE users ADD email VARCHAR(255);"]
        ],
        "Selection" => [
            ["title" => "Select All", "code" => "SELECT * FROM users;"],
            ["title" => "Select Columns", "code" => "SELECT id, name FROM users;"],
            ["title" => "Select Distinct", "code" => "SELECT DISTINCT name FROM users;"],
            ["title" => "Select with Order", "code" => "SELECT * FROM users ORDER BY name ASC;"]
        ],
        "Filtering" => [
            ["title" => "Where Clause", "code" => "SELECT * FROM users WHERE id = 1;"],
            ["title" => "Where with AND", "code" => "SELECT * FROM users WHERE id > 0 AND active = 1;"],
            ["title" => "Where with LIKE", "code" => "SELECT * FROM users WHERE name LIKE 'J%';"],
            ["title" => "Where with IN", "code" => "SELECT * FROM users WHERE id IN (1, 2, 3);"]
        ]
    ],
    "HTML" => [
        "Base Structure" => [
            ["title" => "HTML Document", "code" => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Title</title>\n</head>\n<body>\n    <!-- content -->\n</body>\n</html>"],
            ["title" => "Meta Charset", "code" => "<meta charset=\"UTF-8\">"],
            ["title" => "Link Favicon", "code" => "<link rel=\"icon\" href=\"favicon.ico\" type=\"image/x-icon\">"],
            ["title" => "HTML Comment", "code" => "<!-- This is a comment -->"]
        ],
        "HTML Styles" => [
            ["title" => "Inline Style", "code" => "<p style=\"color: red;\">Text</p>"],
            ["title" => "Internal Style", "code" => "<style>\n    p { color: blue; }\n</style>"],
            ["title" => "External Style Link", "code" => "<link rel=\"stylesheet\" href=\"styles.css\">"],
            ["title" => "Class Attribute", "code" => "<p class=\"highlight\">Text</p>"]
        ],
        "HTML in PHP" => [
            ["title" => "Echo HTML", "code" => "<?php echo '<p>Hello</p>'; ?>"],
            ["title" => "PHP Variable in HTML", "code" => "<p><?php echo \$name; ?></p>"],
            ["title" => "PHP Loop in HTML", "code" => "<?php for(\$i=0; \$i<3; \$i++) { ?>\n    <p>Item <?php echo \$i; ?></p>\n<?php } ?>"],
            ["title" => "Conditional HTML", "code" => "<?php if(\$loggedIn) { ?>\n    <p>Welcome!</p>\n<?php } ?>"]
        ],
        "HTML and Databases" => [
            ["title" => "Display Data", "code" => "<table>\n    <?php foreach (\$data as \$row) { ?>\n    <tr><td><?php echo \$row['field']; ?></td></tr>\n    <?php } ?>\n</table>"],
            ["title" => "Dynamic List", "code" => "<ul>\n    <?php foreach (\$items as \$item) { ?>\n    <li><?php echo \$item; ?></li>\n    <?php } ?>\n</ul>"],
            ["title" => "Table Headers", "code" => "<table>\n    <tr><th>ID</th><th>Name</th></tr>\n    <?php foreach (\$data as \$row) { ?>\n    <tr><td><?php echo \$row['id']; ?></td><td><?php echo \$row['name']; ?></td></tr>\n    <?php } ?>\n</table>"],
            ["title" => "Form with DB Data", "code" => "<form>\n    <select>\n    <?php foreach (\$options as \$opt) { ?>\n    <option value=\"<?php echo \$opt['id']; ?>\"><?php echo \$opt['name']; ?></option>\n    <?php } ?>\n    </select>\n</form>"]
        ]
    ],
    "CSS" => [
        "Base Structure" => [
            ["title" => "CSS Rule", "code" => "selector {\n    property: value;\n}"],
            ["title" => "Inline CSS", "code" => "<p style=\"color: red;\">Text</p>"],
            ["title" => "External CSS", "code" => "<link rel=\"stylesheet\" href=\"styles.css\">"],
            ["title" => "Internal CSS", "code" => "<style>\n    p { color: blue; }\n</style>"]
        ],
        "Selectors" => [
            ["title" => "Class Selector", "code" => ".className {\n    color: blue;\n}"],
            ["title" => "ID Selector", "code" => "#myId {\n    font-size: 20px;\n}"],
            ["title" => "Element Selector", "code" => "p {\n    margin: 10px;\n}"],
            ["title" => "Universal Selector", "code" => "* {\n    padding: 0;\n}"]
        ],
        "Layout" => [
            ["title" => "Flexbox", "code" => ".container {\n    display: flex;\n}"],
            ["title" => "Grid", "code" => ".container {\n    display: grid;\n    grid-template-columns: 1fr 1fr;\n}"],
            ["title" => "Float Layout", "code" => ".left {\n    float: left;\n    width: 50%;\n}"],
            ["title" => "Position Absolute", "code" => ".box {\n    position: absolute;\n    top: 10px;\n}"]
        ],
        "Responsive Design" => [
            ["title" => "Media Query", "code" => "@media (max-width: 600px) {\n    body {\n        background-color: lightblue;\n    }\n}"],
            ["title" => "Viewport Meta", "code" => "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">"],
            ["title" => "Relative Units", "code" => ".box {\n    width: 50vw;\n    height: 20vh;\n}"],
            ["title" => "Min/Max Width", "code" => ".container {\n    max-width: 1200px;\n    min-width: 300px;\n}"]
        ]
    ],
    "PHP" => [
        "Base Structure" => [
            ["title" => "PHP Tag", "code" => "<?php\n    // code\n?>"],
            ["title" => "Echo Statement", "code" => "<?php echo \"Hello, World!\"; ?>"],
            ["title" => "Variable Declaration", "code" => "<?php \$name = \"John\"; ?>"],
            ["title" => "Comment", "code" => "<?php\n    // This is a comment\n    # Another comment\n    /* Multi-line comment */\n?>"]
        ],
        "Functions" => [
            ["title" => "Function Definition", "code" => "function myFunction() {\n    // code\n}"],
            ["title" => "Function with Return", "code" => "function add(\$a, \$b) {\n    return \$a + \$b;\n}"],
            ["title" => "Default Parameter", "code" => "function greet(\$name = \"Guest\") {\n    echo \"Hello, \$name\";\n}"],
            ["title" => "Anonymous Function", "code" => "\$say = function() {\n    echo \"Hello\";\n};"]
        ],
        "Single Page PHP" => [
            ["title" => "Form Handling", "code" => "if (\$_SERVER['REQUEST_METHOD'] == 'POST') {\n    // process form\n}"],
            ["title" => "Get Form Data", "code" => "\$input = \$_POST['input'] ?? '';"],
            ["title" => "Redirect", "code" => "header('Location: index.php');\nexit;" ],
            ["title" => "Session Start", "code" => "session_start();\n\$_SESSION['user'] = 'John';"]
        ],
        "PHP and Databases" => [
            ["title" => "MySQL Connection", "code" => "\$conn = new mysqli('host', 'user', 'pass', 'db');"],
            ["title" => "Query Execution", "code" => "\$result = \$conn->query(\"SELECT * FROM users\");"],
            ["title" => "Fetch Data", "code" => "while(\$row = \$result->fetch_assoc()) {\n    echo \$row['name'];\n}"],
            ["title" => "Prepared Statement", "code" => "\$stmt = \$conn->prepare(\"INSERT INTO users (name) VALUES (?)\");\n\$stmt->bind_param('s', \$name);"]
        ]
    ],
    "Cisco Networking & Security" => [
        "Basic Router Config" => [
            ["title" => "Set Hostname", "code" => "Router(config)# hostname MyRouter"],
            ["title" => "Interface Config", "code" => "Router(config)# interface FastEthernet0/0\nRouter(config-if)# ip address 192.168.1.1 255.255.255.0"],
            ["title" => "Enable Interface", "code" => "Router(config-if)# no shutdown"],
            ["title" => "Set Description", "code" => "Router(config-if)# description LAN Interface"]
        ],
        "Basic Switch Config" => [
            ["title" => "Set VLAN", "code" => "Switch(config)# vlan 10"],
            ["title" => "Name VLAN", "code" => "Switch(config-vlan)# name SALES"],
            ["title" => "Set Switch Hostname", "code" => "Switch(config)# hostname MySwitch"],
            ["title" => "Enable Switchport", "code" => "Switch(config-if)# no shutdown"]
        ],
        "Basic Networking Security" => [
            ["title" => "Enable Password", "code" => "Router(config)# enable secret mypassword"],
            ["title" => "Console Password", "code" => "Router(config)# line console 0\nRouter(config-line)# password cisco"],
            ["title" => "VTY Password", "code" => "Router(config)# line vty 0 4\nRouter(config-line)# password cisco"],
            ["title" => "Login Requirement", "code" => "Router(config-line)# login"]
        ],
        "ACLs" => [
            ["title" => "Standard ACL", "code" => "Router(config)# access-list 1 deny 192.168.1.0 0.0.0.255"],
            ["title" => "Extended ACL", "code" => "Router(config)# access-list 100 permit tcp 192.168.1.0 0.0.0.255 any eq 80"],
            ["title" => "Apply ACL", "code" => "Router(config)# interface FastEthernet0/0\nRouter(config-if)# ip access-group 100 in"],
            ["title" => "Named ACL", "code" => "Router(config)# ip access-list standard BLOCK\nRouter(config-std-nacl)# deny 10.0.0.0 0.255.255.255"]
        ],
        "VLANS" => [
            ["title" => "Assign Port to VLAN", "code" => "Switch(config-if)# switchport access vlan 10"],
            ["title" => "Trunk Port", "code" => "Switch(config-if)# switchport mode trunk"],
            ["title" => "VLAN Name", "code" => "Switch(config)# vlan 20\nSwitch(config-vlan)# name ENGINEERING"],
            ["title" => "Show VLANs", "code" => "Switch# show vlan brief"]
        ],
        "Access Levels" => [
            ["title" => "User Mode", "code" => "Router>"],
            ["title" => "Privileged Mode", "code" => "Router# enable"],
            ["title" => "Global Config Mode", "code" => "Router# configure terminal"],
            ["title" => "Interface Config Mode", "code" => "Router(config)# interface FastEthernet0/0"]
        ]
    ],
    "Systems Security" => [
        "Module 1: Securing Networks" => [
            ["title" => "Configure VLAN", "code" => "interface vlan 10\n ip address 192.168.10.1 255.255.255.0\n description Secure VLAN\n no shutdown"],
            ["title" => "Enable SSH", "code" => "crypto key generate rsa\n ip ssh version 2"],
            ["title" => "Set IP on Interface", "code" => "interface FastEthernet0/0\n ip address 192.168.1.1 255.255.255.0\n no shutdown"],
            ["title" => "Show VLAN Info", "code" => "show vlan brief"]
        ],
        "Module 2: Network Threats" => [
            ["title" => "Log DDoS Attempts", "code" => "logging trap warnings\nlogging host 192.168.1.100"],
            ["title" => "Enable Logging", "code" => "logging on\nlogging buffered 51200 debugging"],
            ["title" => "Show Logs", "code" => "show logging"],
            ["title" => "Set Log Timestamp", "code" => "service timestamps log datetime"]
        ],
        "Module 3: Mitigating Threats" => [
            ["title" => "Rate-Limit Traffic", "code" => "interface FastEthernet0/0\n rate-limit input 1000000 187500 375000 conform-action transmit exceed-action drop"],
            ["title" => "Traffic Shaping", "code" => "interface FastEthernet0/0\n traffic-shape rate 800000"],
            ["title" => "Show Rate Limits", "code" => "show interfaces FastEthernet0/0 rate-limit"],
            ["title" => "Set Bandwidth Limit", "code" => "interface FastEthernet0/0\n bandwidth 10000"]
        ],
        "Module 4: Securing Device Access" => [
            ["title" => "Secure Console", "code" => "line console 0\n password C1sc0\n login\n exec-timeout 5 0"],
            ["title" => "Secure VTY", "code" => "line vty 0 4\n password C1sc0\n login"],
            ["title" => "Disable Telnet", "code" => "line vty 0 4\n transport input ssh"],
            ["title" => "Set Banner", "code" => "banner motd #Authorized Access Only#"]
        ],
        "Module 5: Assigning Administrative Roles" => [
            ["title" => "Set Privilege Level", "code" => "username admin privilege 15 secret C1sc0\nprivilege exec level 15 configure terminal"],
            ["title" => "Create User", "code" => "username user1 privilege 5 secret MyPass"],
            ["title" => "Show Privilege", "code" => "show privilege"],
            ["title" => "Custom Privilege", "code" => "privilege exec level 5 show running-config"]
        ],
        "Module 6: Device Monitoring and Management" => [
            ["title" => "Enable SNMP", "code" => "snmp-server community public RO\nsnmp-server host 192.168.1.50 public"],
            ["title" => "Set SNMP Traps", "code" => "snmp-server enable traps\nsnmp-server host 192.168.1.50 traps public"],
            ["title" => "Show SNMP", "code" => "show snmp"],
            ["title" => "Enable NetFlow", "code" => "interface FastEthernet0/0\n ip flow ingress"]
        ],
        "Module 7: Authentication, Authorization, and Accounting (AAA)" => [
            ["title" => "Configure AAA with RADIUS", "code" => "aaa new-model\nradius-server host 192.168.1.10 key R@diusKey\naaa authentication login default group radius local"],
            ["title" => "TACACS+ Config", "code" => "tacacs-server host 192.168.1.20 key T@cKey\naaa authentication login default group tacacs+ local"],
            ["title" => "Enable Accounting", "code" => "aaa accounting exec default start-stop group radius"],
            ["title" => "Show AAA", "code" => "show running-config | section aaa"]
        ],
        "Module 8: Access Control Lists" => [
            ["title" => "Allow HTTP Traffic", "code" => "access-list 101 permit tcp 192.168.1.0 0.0.0.255 any eq 80\naccess-list 101 deny ip any any\ninterface FastEthernet0/0\n ip access-group 101 in"],
            ["title" => "Deny ICMP", "code" => "access-list 102 deny icmp any any\ninterface FastEthernet0/0\n ip access-group 102 in"],
            ["title" => "Show ACLs", "code" => "show access-lists"],
            ["title" => "Named ACL", "code" => "ip access-list extended HTTP_ONLY\n permit tcp any any eq 80\n deny ip any any"]
        ],
        "Module 9: Firewall Technologies" => [
            ["title" => "Reflexive ACL", "code" => "access-list 102 permit tcp any any eq 80 reflect WEBTRAFFIC\ninterface FastEthernet0/0\n ip access-group 102 in"],
            ["title" => "Basic Firewall Rule", "code" => "access-list 103 permit tcp any any eq 443\ninterface FastEthernet0/0\n ip access-group 103 in"],
            ["title" => "Log Traffic", "code" => "access-list 104 permit ip any any log\ninterface FastEthernet0/0\n ip access-group 104 in"],
            ["title" => "Clear Logs", "code" => "clear logging"]
        ],
        "Module 10: Zone-Based Policy Firewalls" => [
            ["title" => "Basic ZBPF Config", "code" => "zone security INSIDE\nzone security OUTSIDE\ninterface FastEthernet0/0\n zone-member security INSIDE\nclass-map type inspect match-all HTTP\n match protocol http\npolicy-map type inspect INSIDE-TO-OUTSIDE\n class HTTP\n  inspect"],
            ["title" => "Zone Pair", "code" => "zone-pair security IN-TO-OUT source INSIDE destination OUTSIDE\n service-policy type inspect INSIDE-TO-OUTSIDE"],
            ["title" => "Show Zones", "code" => "show zone security"],
            ["title" => "Drop Traffic", "code" => "policy-map type inspect DROP\n class class-default\n  drop"]
        ],
        "Module 11: IPS Technologies" => [
            ["title" => "Enable IPS", "code" => "ip ips name MYIPS\nip ips signature-category\n category all\n retired false"],
            ["title" => "Set IPS Signature", "code" => "ip ips signature 2000:0 disable"],
            ["title" => "Show IPS Config", "code" => "show ip ips configuration"],
            ["title" => "Update IPS", "code" => "ip ips update"]
        ],
        "Module 12: IPS Operation and Implementation" => [
            ["title" => "Apply IPS", "code" => "interface FastEthernet0/0\n ip ips MYIPS in"],
            ["title" => "Show IPS Events", "code" => "show ip ips events"],
            ["title" => "Log IPS", "code" => "ip ips name MYIPS log"],
            ["title" => "Test IPS", "code" => "show ip ips statistics"]
        ],
        "Module 13: Endpoint Security" => [
            ["title" => "Port Security", "code" => "interface FastEthernet0/1\n switchport mode access\n switchport port-security\n switchport port-security maximum 2\n switchport port-security violation shutdown"],
            ["title" => "Sticky MAC", "code" => "interface FastEthernet0/1\n switchport port-security mac-address sticky"],
            ["title" => "Show Port Security", "code" => "show port-security"],
            ["title" => "Set Aging", "code" => "interface FastEthernet0/1\n switchport port-security aging time 10"]
        ],
        "Module 14: Layer 2 Security Considerations" => [
            ["title" => "Prevent VLAN Hopping", "code" => "interface FastEthernet0/1\n switchport mode access\n switchport access vlan 10"],
            ["title" => "Enable DAI", "code" => "ip arp inspection vlan 10"],
            ["title" => "Show DAI", "code" => "show ip arp inspection"],
            ["title" => "Disable Trunk", "code" => "interface FastEthernet0/1\n switchport mode access"]
        ],
        "Module 15: Cryptographic Services" => [
            ["title" => "Generate RSA Keys", "code" => "crypto key generate rsa\n 1024"],
            ["title" => "Show Keys", "code" => "show crypto key mypubkey rsa"],
            ["title" => "Set AES", "code" => "crypto ipsec transform-set MYSET esp-aes"],
            ["title" => "Remove Keys", "code" => "no crypto key rsa"]
        ],
        "Module 16: Basic Integrity and Authenticity" => [
            ["title" => "HMAC-SHA1 Auth", "code" => "interface FastEthernet0/0\n ip authentication mode eigrp 100 md5\n ip authentication key-chain eigrp 100 MYCHAIN"],
            ["title" => "Key Chain", "code" => "key chain MYCHAIN\n key 1\n  key-string MyKey"],
            ["title" => "Show Auth", "code" => "show ip eigrp neighbors"],
            ["title" => "Enable MD5", "code" => "interface FastEthernet0/0\n ip ospf authentication message-digest"]
        ],
        "Module 17: Public Key Cryptography" => [
            ["title" => "Configure SSH with RSA", "code" => "ip domain-name example.com\ncrypto key generate rsa\n 2048\nip ssh version 2"],
            ["title" => "Show SSH", "code" => "show ip ssh"],
            ["title" => "Import Cert", "code" => "crypto pki import MYCERT"],
            ["title" => "Set SSH Timeout", "code" => "ip ssh time-out 60"]
        ],
        "Module 18: VPNs" => [
            ["title" => "IPsec VPN Setup", "code" => "crypto isakmp policy 10\n encryption aes 256\n authentication pre-share\ncrypto isakmp key MySecretKey address 203.0.113.2"],
            ["title" => "Show VPN", "code" => "show crypto isakmp sa"],
            ["title" => "Set Transform", "code" => "crypto ipsec transform-set MYSET esp-aes 256 esp-sha-hmac"],
            ["title" => "Enable NAT-T", "code" => "crypto isakmp nat-traversal"]
        ],
        "Module 19: Implement Site-to-Site IPsec VPNs" => [
            ["title" => "Site-to-Site VPN", "code" => "crypto ipsec transform-set MYSET esp-aes 256 esp-sha-hmac\ncrypto map MYMAP 10 ipsec-isakmp\n set peer 203.0.113.2\n set transform-set MYSET"],
            ["title" => "Apply Crypto Map", "code" => "interface FastEthernet0/0\n crypto map MYMAP"],
            ["title" => "Show IPsec SA", "code" => "show crypto ipsec sa"],
            ["title" => "Set ACL", "code" => "access-list 110 permit ip 192.168.1.0 0.0.0.255 10.0.0.0 0.0.0.255"]
        ],
        "Module 20: Introduction to the ASA" => [
            ["title" => "ASA Interface Config", "code" => "interface GigabitEthernet0/0\n nameif OUTSIDE\n security-level 0\n ip address 203.0.113.1 255.255.255.0"],
            ["title" => "Set Hostname", "code" => "hostname ASA1"],
            ["title" => "Show Mode", "code" => "show running-config | include mode"],
            ["title" => "Enable Multi-Context", "code" => "mode multiple"]
        ],
        "Module 21: ASA Firewall Configuration" => [
            ["title" => "ASA NAT Config", "code" => "object network INSIDE-NET\n subnet 192.168.1.0 255.255.255.0\n nat (INSIDE,OUTSIDE) dynamic interface"],
            ["title" => "ACL on ASA", "code" => "access-list OUTSIDE_IN extended permit tcp any any eq 80\naccess-group OUTSIDE_IN in interface OUTSIDE"],
            ["title" => "Show NAT", "code" => "show nat"],
            ["title" => "Set Security Level", "code" => "interface GigabitEthernet0/1\n nameif INSIDE\n security-level 100"]
        ],
        "Module 22: Network Security Testing" => [
            ["title" => "Enable Logging", "code" => "logging enable\nlogging timestamp\nlogging buffered debugging"],
            ["title" => "Nmap Scan", "code" => "nmap -sS 192.168.1.0/24"],
            ["title" => "Wireshark Capture", "code" => "wireshark -i eth0 -f \"tcp port 80\""],
            ["title" => "Show Logs", "code" => "show logging"]
        ]
    ]
];
}

?>



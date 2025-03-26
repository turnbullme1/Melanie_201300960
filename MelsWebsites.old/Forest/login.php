<?php
session_start();
$users = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'register') {
        $username = htmlspecialchars($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $users[$username] = $password;
        echo "Registration successful! Please log in.";
    } elseif ($action === 'login') {
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            $_SESSION['user_id'] = rand(1, 1000);
            $_SESSION['username'] = $username;
            header("Location: forum.php");
        } else {
            echo "Invalid login credentials.";
        }
    } elseif ($action === 'logout') {
        session_destroy();
        header("Location: login.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Zephy's Forest</title>

</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <h1>Login / Register</h1>
        <form method="POST">
            <input type="hidden" name="action" value="register">
            <label>Username: <input type="text" name="username" placeholder="Username" required></label>
            <label>Password: <input type="password" name="password" placeholder="Password" required></label>
            <button type="submit">Register</button>
        </form>
        <form method="POST">
            <input type="hidden" name="action" value="login">
            <label>Username: <input type="text" name="username" placeholder="Username" required></label>
            <label>Password: <input type="password" name="password" placeholder="Password" required></label>
            <button type="submit">Login</button>
        </form>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
<!-- views/login.php -->
<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$message = $message ?? '';
?>
<div class="container mt-4">
    <h1 class="text-center mb-4"><?php echo isset($_GET['action']) && $_GET['action'] === 'signup' ? 'Sign Up' : 'Login'; ?></h1>

    <?php if ($message || (isset($_GET['signup']) && $_GET['signup'] === 'success')): ?>
        <div class="alert <?= $message && strpos($message, 'success') === false ? 'alert-danger' : 'alert-success' ?>">
            <?php
            if (isset($_GET['signup']) && $_GET['signup'] === 'success') {
                echo 'Signup successful! Please log in.';
            } else {
                echo htmlspecialchars($message);
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['action']) && $_GET['action'] === 'signup'): ?>
        <!-- Signup Form -->
        <form method="post" action="index.php?action=signup" class="mb-3">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="request_admin" name="request_admin" <?= isset($_POST['request_admin']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="request_admin">Request Admin Privileges</label>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
            <a href="index.php?action=login" class="btn btn-secondary">Back to Login</a>
        </form>
    <?php else: ?>
        <!-- Login Form -->
        <form method="post" action="index.php?action=login" class="mb-3">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php?action=signup" class="btn btn-secondary">Sign Up</a>
        </form>
    <?php endif; ?>
</div>
<?php
if (!defined('IN_CONTROLLER')) {
    die("Direct access to this page is not allowed.");
}
$message = $message ?? 'Page not found';
?>
<div class="container my-5">
    <h1 class="display-5 fw-bold text-center text-danger">404 - Not Found</h1>
    <p class="text-center text-muted"><?php echo htmlspecialchars($message); ?></p>
    <div class="text-center mt-4">
        <a href="index.php?action=home" class="btn btn-primary">Return Home</a>
    </div>
</div>
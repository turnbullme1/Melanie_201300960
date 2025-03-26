<?php
$page_title = "Contact - Melanie's Portfolio";
$current_page = "contact";
require_once 'includes/header.php';

// Handle form submission (basic example)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    // Add your email processing logic here (e.g., mail())
    $form_message = "Thank you, $name! Your message has been sent.";
}
?>

<section id="contact" class="elegant-section">
    <h2>Contact Me</h2>
    <p class="section-subtitle">I’d love to hear from you—please reach out</p>

    <?php if (isset($form_message)): ?>
        <p class="form-feedback"><?php echo $form_message; ?></p>
    <?php endif; ?>

    <form action="contact.php" method="POST" class="contact-form">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="submit-btn">Send Message</button>
    </form>
</section>

<?php require_once 'includes/footer.php'; ?>
<?php
// pages/contact.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$title = "Contact Us";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container">
    <h1>Contact Us</h1>
    <p>Get in touch with our team.</p>
    
    <form class="contact-form">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn">Send Message</button>
    </form>
</main>

<?php
require_once '../includes/footer.php';
?>
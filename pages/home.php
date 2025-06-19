<?php
// pages/home.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$title = "Home";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>This is the home page of your website. You can add any content here.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris.</p>
</main>

<?php
require_once '../includes/footer.php';
?>
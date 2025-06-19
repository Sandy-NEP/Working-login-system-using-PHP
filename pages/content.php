<?php
// pages/content.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
$title = "Content";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container">
    <h1>Content Page</h1>
    <p>Here you can display various content for your users.</p>
    <div class="content-grid">
        <div class="content-item">
            <h3>Article 1</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        </div>
        <div class="content-item">
            <h3>Article 2</h3>
            <p>Vivamus luctus urna sed urna ultricies ac tempor dui sagittis.</p>
        </div>
    </div>
</main>

<?php
require_once '../includes/footer.php';
?>
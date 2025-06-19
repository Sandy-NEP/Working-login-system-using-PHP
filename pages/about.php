<?php
// pages/about.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$title = "About Us";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container">
    <h1>About Us</h1>
    <p>Learn more about our company and mission.</p>
    <section>
        <h2>Our Story</h2>
        <p>Founded in 2023, we've been dedicated to providing excellent service to our customers.</p>
    </section>
    <section>
        <h2>Our Team</h2>
        <p>We have a talented team of professionals committed to your success.</p>
    </section>
</main>

<?php
require_once '../includes/footer.php';
?>
<?php
// includes/nav.php
if (!isset($_SESSION['user_id'])) {
    return;
}
?>
<nav class="navbar">
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="../pages/home.php">Home</a></li>
            <li><a href="../pages/content.php">Content</a></li>
            <li><a href="../pages/about.php">About</a></li>
            <li><a href="../pages/contact.php">Contact</a></li>
        </ul>
        
        <div class="user-profile">
            <div class="profile-icon">
                <a href="../profile/verify_password.php"><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></a>
            </div>
        </div>
    </div>
</nav>
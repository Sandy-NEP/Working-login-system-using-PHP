<?php
// profile/delete.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        session_unset();
        session_destroy();
        header('Location: ../auth/login.php?account=deleted');
        exit();
    } else {
        $error = "Failed to delete account. Please try again.";
    }
}

$title = "Delete Account";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container">
    <h1>Delete Account</h1>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <div class="warning-box">
        <h2>Warning!</h2>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <p>All your data will be permanently removed from our systems.</p>
        
        <form action="delete.php" method="POST">
            <button type="submit" class="btn delete-btn">Yes, Delete My Account</button>
            <a href="../pages/home.php" class="btn cancel-btn">Cancel</a>
        </form>
    </div>
</main>

<?php
require_once '../includes/footer.php';
?>
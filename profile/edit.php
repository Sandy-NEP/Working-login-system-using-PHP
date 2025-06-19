<?php
// profile/edit.php
require_once '../includes/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Require password verification
if (!isset($_SESSION['password_verified']) || $_SESSION['password_verified'] !== true) {
    header('Location: verify_password.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $new_password = $_POST['password'];
    
    // Begin transaction
    $conn->begin_transaction();
    try {
        // Update user data
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $email, $phone, $new_password, $_SESSION['user_id']);
        
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['phone'] = $phone;
            
            // Record change in history
            $history_stmt = $conn->prepare("INSERT INTO user_data_history (user_id, changed_field, old_value, new_value, changed_at, changed_by) 
                                          VALUES (?, ?, ?, ?, NOW(), ?)");
            $changed_by = 'self_edit';
            $field = 'profile';
            $old_value = json_encode([
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'phone' => $_SESSION['phone']
            ]);
            $new_value = json_encode([
                'username' => $username,
                'email' => $email,
                'phone' => $phone
            ]);
            $history_stmt->bind_param("issss", $_SESSION['user_id'], $field, $old_value, $new_value, $changed_by);
            $history_stmt->execute();
            
            $conn->commit();
            
            // Clear password verification flag
            unset($_SESSION['password_verified']);
            
            $success = "Profile updated successfully!";
        } else {
            throw new Exception("Failed to update profile");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Failed to update profile. Please try again.";
    }
}

// Get current user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$title = "Edit Profile";
require_once '../includes/header.php';
require_once '../includes/nav.php';
?>

<main class="container profile-container">
    <h1>Edit Profile</h1>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="edit.php" method="POST" class="profile-form">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user['password']); ?>" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Profile</button>
            <a href="../auth/logout.php" class="btn btn-secondary">Logout</a>
            <a href="../profile/delete.php" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete your account? This cannot be undone!')">Delete Account</a>
        </div>
    </form>
</main>

<?php
require_once '../includes/footer.php';
?>
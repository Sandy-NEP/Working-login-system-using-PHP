<?php
require_once '../includes/config.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if no verified reset data exists
if (!isset($_SESSION['reset_data']) || !isset($_SESSION['reset_data']['verified']) || $_SESSION['reset_data']['verified'] !== true) {
    header("Location: forgot.php");
    exit();
}

// Check if password was already reset
if (isset($_SESSION['reset_completed']) && $_SESSION['reset_completed'] === true) {
    unset($_SESSION['reset_data']);
    unset($_SESSION['reset_completed']);
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validate inputs
    if (empty($new_password) || empty($confirm_password)) {
        $error = "Please fill in all fields.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($new_password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        // Verify user exists
        $user_id = $_SESSION['reset_data']['user_id'];
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE id = ?");
        $check_stmt->bind_param("i", $user_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows === 0) {
            $error = "User not found. Please try the reset process again.";
        } else {
            // Hash the new password (recommended)
            // $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // For plain text (not recommended)
            $plain_password = $new_password;
            
            // Begin transaction
            $conn->begin_transaction();
            
            try {
                // Update password
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $plain_password, $user_id);
                $update_result = $update_stmt->execute();
                
                if (!$update_result) {
                    throw new Exception("Password update failed: " . $conn->error);
                }
                
                // Record change in history
                $history_stmt = $conn->prepare("INSERT INTO user_data_history (user_id, changed_field, old_value, new_value, changed_at, changed_by) 
                                              VALUES (?, ?, ?, ?, NOW(), ?)");
                $changed_by = 'self_reset';
                $field = 'password';
                $old_value = '';
                $history_stmt->bind_param("issss", $user_id, $field, $old_value, $plain_password, $changed_by);
                $history_result = $history_stmt->execute();
                
                if (!$history_result) {
                    throw new Exception("History record failed: " . $conn->error);
                }
                
                // Commit transaction
                $conn->commit();
                
                // Mark reset as completed
                $_SESSION['reset_completed'] = true;
                $success = "Password updated successfully! Redirecting to login page...";
                
                // Redirect after 3 seconds
                header("Refresh: 3; url=login.php");
            } catch (Exception $e) {
                $conn->rollback();
                $error = "An error occurred. Please try again.";
                error_log("Password reset error: " . $e->getMessage());
            }
        }
    }
}

// Get user details for display
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['reset_data']['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../assets/css/auth.css">

</head>
<body>
    <div class="auth-container">
        <h2>Reset Your Password</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
        <?php else: ?>
            <div class="user-info">
                <p><strong>Account:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <br>
            <form action="reset.php" method="POST" id="resetForm">
                <div class="form-group password-field">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required minlength="8" class="form-control" placeholder="Enter new password (min 8 characters)">
                    <button type="button" class="toggle-password" data-target="new_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                
                <div class="form-group password-field">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8" class="form-control" placeholder="Confirm your new password">
                    <button type="button" class="toggle-password" data-target="confirm_password">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>

                <button type="submit" class="btn">
                    <i class="fas fa-key"></i> Reset Password
                </button>
            </form>
        <?php endif; ?>
    </div>

    
</body>
</html>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script type="module" src="../assets/js/auth.js"></script>
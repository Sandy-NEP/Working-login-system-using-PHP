<?php
require_once '../includes/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = '';
$show_verification_form = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if this is the verification code submission
    if (isset($_POST['verification_code']) && isset($_SESSION['reset_data'])) {
        $user_code = trim($_POST['verification_code']);
        
        if (empty($user_code)) {
            $error = "Please enter the verification code.";
        } elseif ($user_code === $_SESSION['reset_data']['code']) {
            // Code verified, redirect to password reset
            $_SESSION['reset_data']['verified'] = true;
            header("Location: reset.php");
            exit();
        } else {
            $_SESSION['reset_data']['attempts']++;
            $error = "Invalid verification code. Attempts remaining: " . (3 - $_SESSION['reset_data']['attempts']);
            
            if ($_SESSION['reset_data']['attempts'] >= 3) {
                unset($_SESSION['reset_data']);
                $error = "Too many failed attempts. Please start over.";
                $show_verification_form = false;
            } else {
                $show_verification_form = true;
            }
        }
    } 
    // Handle initial email/phone submission
    else {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        
        if (empty($email) || empty($phone)) {
            $error = "Please fill in all fields.";
        } else {
            $stmt = $conn->prepare("SELECT id, username FROM users WHERE email = ? AND phone = ?");
            $stmt->bind_param("ss", $email, $phone);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                
                // Generate secure verification code (6 digits)
                $verification_code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                // Store code in session
                $_SESSION['reset_data'] = [
                    'user_id' => $user['id'],
                    'code' => $verification_code,
                    'expires_at' => $expires_at,
                    'attempts' => 0,
                    'verified' => false
                ];
                
                // In a real application, you would send this code via email/SMS
                // For demo purposes, we'll just display it
                $error = "Verification code: ($verification_code)";//(This would be sent to your email/phone in production)";
                $show_verification_form = true;
            } else {
                $error = "No account found with matching email and phone number.";
                $show_verification_form = false;
            }
        }
    }
}

// Show verification form if we have reset data and no errors
if ($show_verification_form && isset($_SESSION['reset_data'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verify Account</title>
        
    </head>
    <link rel="stylesheet" href="../assets/css/auth.css">
    <body>
        <div class="auth-container">
            <h2>Verify Your Account</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form action="forgot.php" method="POST">
                <div class="form-group">
                    
                    <label for="verification_code">Verification Code</label>
                    <input type="text" id="verification_code" name="verification_code" required class="form-control" 
                           placeholder="Enter 6-digit code">
                </div>
                <button type="submit" class="btn">Verify Code</button>
            </form>
            
            <p><a href="forgot.php">Start Over</a></p>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <title>Account Recovery</title>
</head>
<body>
    <div class="auth-container">
        <h2>Account Recovery</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="forgot.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required class="form-control">
            </div>
            <button type="submit" class="btn">Verify Account</button>
        </form>
        
        <p>Remember your password? <a href="login.php">Login</a></p>
    </div>
</body>
</html><script type="module" src="../assets/js/auth.js"></script>
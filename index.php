<?php
// index.php
require_once 'includes/config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: pages/home.php');
    exit();
} else {
    header('Location: auth/login.php');
    exit();
}
?>
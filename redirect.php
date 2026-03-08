<?php
include("includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

if ($_SESSION['role'] === 'admin') {
    header("Location: " . BASE_URL . "admin/dashboard.php");
} else {
    header("Location: " . BASE_URL . "dashboard.php");
}
exit;

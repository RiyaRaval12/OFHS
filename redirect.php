<?php
include("includes/db.php");

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: /Online%20Food%20Helpline%20System/auth/login.php");
    exit;
}

switch ($_SESSION['role']) {
    case 'admin':
        header("Location: admin/dashboard.php");
        break;
    case 'donor':
        header("Location: donor/dashboard.php");
        break;
    case 'receiver':
        header("Location: receiver/dashboard.php");
        break;
    case 'volunteer':
        header("Location: volunteer/dashboard.php");
        break;
    default:
        header("Location: /Online%20Food%20Helpline%20System/auth/login.php");
        break;
}
exit;

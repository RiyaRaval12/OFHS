<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: /Online%20Food%20Helpline%20System/auth/login.php");
    exit;
}
?>

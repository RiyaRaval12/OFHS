<?php
// Global DB + app bootstrap
if (!defined('BASE_URL')) {
    // URL-encoded because folder has spaces.
    define('BASE_URL', '/Online%20Food%20Helpline%20System/');
}

$conn = new PDO(
    "mysql:host=localhost;dbname=food_helpline",
    "root",
    ""
);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// start session for auth
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

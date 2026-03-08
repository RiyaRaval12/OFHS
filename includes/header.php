<?php
include_once __DIR__ . '/db.php';
include_once __DIR__ . '/auth_check.php';

$page_title = $page_title ?? 'FoodHelpline';
$active = $active ?? '';

// Load the signed-in user
$userStmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$userStmt->execute([$_SESSION['user_id']]);
$currentUser = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$currentUser) {
    session_destroy();
    header("Location: " . BASE_URL . "auth/login.php");
    exit;
}

$firstInitial = strtoupper(substr($currentUser['name'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | FoodHelpline</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <script defer src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</head>
<body class="app-body">
<header class="app-nav">
    <div class="brand">
        <a href="<?php echo BASE_URL; ?>dashboard.php">
            <i class="fas fa-utensils"></i>
            <span>FoodHelpline</span>
        </a>
    </div>

    <nav class="nav-links">
        <a class="<?php echo $active === 'dashboard' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>dashboard.php">Dashboard</a>
        <a class="<?php echo $active === 'listings' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>food_listings.php">Food Listings</a>
        <a class="<?php echo $active === 'requests' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>requests.php">Requests</a>
        <a class="<?php echo $active === 'activity' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>activity.php">My Activity</a>
        <?php if ($currentUser['role'] === 'admin'): ?>
            <a class="<?php echo $active === 'admin' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/dashboard.php">Admin</a>
        <?php endif; ?>
    </nav>

    <div class="nav-cta">
        <?php if ($currentUser['role'] === 'receiver'): ?>
            <a class="btn ghost" href="<?php echo BASE_URL; ?>requests.php#new-request"><i class="far fa-heart"></i> Request Help</a>
        <?php elseif ($currentUser['role'] === 'donor'): ?>
            <a class="btn primary" href="<?php echo BASE_URL; ?>food_listings.php#donate"><i class="fas fa-plus"></i> Donate Food</a>
        <?php elseif ($currentUser['role'] === 'volunteer'): ?>
            <a class="btn ghost" href="<?php echo BASE_URL; ?>activity.php"><i class="fas fa-route"></i> View Tasks</a>
        <?php endif; ?>

        <div class="avatar" data-dropdown="user-menu">
            <?php echo htmlspecialchars($firstInitial); ?>
        </div>
        <div class="dropdown" id="user-menu">
            <div class="dropdown-header">
                <div class="avatar small"><?php echo htmlspecialchars($firstInitial); ?></div>
                <div>
                    <div class="name"><?php echo htmlspecialchars($currentUser['name']); ?></div>
                    <div class="role"><?php echo ucfirst($currentUser['role']); ?></div>
                </div>
            </div>
            <a href="<?php echo BASE_URL; ?>profile.php">Profile</a>
            <a href="<?php echo BASE_URL; ?>auth/logout.php" class="logout">Logout</a>
        </div>
    </div>
</header>
<main>

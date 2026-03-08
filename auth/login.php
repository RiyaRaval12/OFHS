<?php
include("../includes/db.php");

$message = $_GET['m'] ?? null;

if (isset($_POST['login'])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: " . BASE_URL . "redirect.php");
            exit;
        } else {
            $error = "Invalid email or password. Please try again.";
        }
    } catch (Exception $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Food Helpline</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <script defer src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="center">

<div class="auth-card">
    <h2><i class="fas fa-sign-in-alt"></i> Login</h2>

    <?php if (isset($message)): ?>
        <div class="flash"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="flash" style="background:#ffe7e6;border-color:#ffcfcf;color:#c62828;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="login" class="btn primary full">Login</button>
    </form>

    <p style="margin-top:16px;">Don't have an account? <a href="register.php">Register</a></p>
</div>
</body>
</html>

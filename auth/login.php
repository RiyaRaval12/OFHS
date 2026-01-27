<?php
include("../includes/db.php");

if (isset($_POST['login'])) {
    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$_POST['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: ../redirect.php");
            exit;
        } else {
            $error = "Invalid Credentials";
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
    <link rel="stylesheet" href="../assets/css/style.css">
    <script defer src="../assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="center">

<div class="auth-card">
    <h2><i class="fas fa-sign-in-alt"></i> Login</h2>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
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

        <button type="submit" name="login" class="btn primary">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<?php if (isset($error)): ?>
    <script>alert('<?php echo $error; ?>');</script>
<?php endif; ?>
</body>
</html>

<?php include("../includes/db.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script defer src="../assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="center">

<div class="auth-card">
    <h2><i class="fas fa-user-plus"></i> Create Account</h2>

    <form method="POST">
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="name" placeholder="Full Name" required>
        </div>

        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

        <div class="input-group">
            <i class="fas fa-user-tag"></i>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="donor">Donor</option>
                <option value="receiver">Receiver</option>
                <option value="volunteer">Volunteer</option>
            </select>
        </div>

        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button type="submit" name="register" class="btn primary">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php
if (isset($_POST['register'])) {
    $stmt = $conn->prepare(
      "INSERT INTO users (name,email,phone,password,role)
       VALUES (?,?,?,?,?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        password_hash($_POST['password'], PASSWORD_DEFAULT),
        $_POST['role']
    ]);
    echo "<script>alert('Registered Successfully'); window.location='login.php';</script>";
}
?>
</body>
</html>

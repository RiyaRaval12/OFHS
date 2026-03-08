<?php
include("../includes/db.php");

$error = null;

if (isset($_POST['register'])) {
    try {
        $name = trim($_POST['name']);
        $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $phone = str_replace([' ', '-'], '', trim($_POST['phone']));
        $address = trim($_POST['address'] ?? '');
        $org = trim($_POST['organization'] ?? '');
        $role = $_POST['role'] ?? '';
        $password = $_POST['password'];

        if (!$email) {
            $error = "Enter a valid email.";
        } elseif (!is_numeric($phone)) {
            $error = "Phone must contain numbers only.";
        } elseif (!in_array($role, ['donor','receiver','volunteer'])) {
            $error = "Select a valid role.";
        } elseif (strlen($password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = "An account with that email already exists.";
            } else {
                $completed = (!empty($phone) && !empty($address)) ? 1 : 0;
                $stmt = $conn->prepare(
                    "INSERT INTO users (name,email,phone,address,organization,password,role,profile_completed)
                     VALUES (?,?,?,?,?,?,?,?)"
                );
                $stmt->execute([
                    $name,
                    $email,
                    $phone,
                    $address,
                    $org,
                    password_hash($password, PASSWORD_DEFAULT),
                    $role,
                    $completed
                ]);
                header("Location: " . BASE_URL . "auth/login.php?m=Account created! Please log in.");
                exit;
            }
        }
    } catch (Exception $e) {
        $error = "Could not register: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Food Helpline</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <script defer src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="center">

<div class="auth-card">
    <h2><i class="fas fa-user-plus"></i> Create Account</h2>

    <?php if (isset($error)): ?>
        <div class="flash" style="background:#ffe7e6;border-color:#ffcfcf;color:#c62828;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

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
            <input type="text" name="phone" placeholder="Phone Number" required inputmode="numeric">
        </div>

        <div class="input-group">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="address" placeholder="Address">
        </div>

        <div class="input-group">
            <i class="fas fa-building"></i>
            <input type="text" name="organization" placeholder="Organization (optional)">
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

        <button type="submit" name="register" class="btn primary full">Register</button>
    </form>

    <p style="margin-top:14px;">Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>

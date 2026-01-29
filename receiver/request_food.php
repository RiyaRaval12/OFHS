<?php include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Food | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Receiver</div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- FORM CONTENT -->
<section class="section">
    <h2>Request Food</h2>

    <div class="auth-card" style="max-width: 600px; margin: 0 auto;">
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-utensils"></i>
                <input type="text" name="food_type" placeholder="Food Type Needed" required>
            </div>

            <div class="input-group">
                <i class="fas fa-weight"></i>
                <input type="text" name="quantity_needed" placeholder="Quantity Needed" required>
            </div>

            <div class="input-group">
                <i class="fas fa-exclamation-triangle"></i>
                <select name="urgency" required>
                    <option value="">Select Urgency</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Delivery Location" required>
            </div>

            <div class="input-group">
                <i class="fas fa-comment"></i>
                <textarea name="description" placeholder="Description" rows="3"></textarea>
            </div>

            <button type="submit" name="request_food" class="btn primary">Submit Request</button>
        </form>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Receiver Panel</p>
</footer>

<?php
if (isset($_POST['request_food'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO food_requests (receiver_id, food_type, quantity_needed, urgency, location, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $_POST['food_type'], $_POST['quantity_needed'], $_POST['urgency'], $_POST['location'], $_POST['description']]);
        echo "<script>alert('Food request submitted successfully!'); window.location='dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
</body>
</html>

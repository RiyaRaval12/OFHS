<?php include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Donation | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Donor</div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- FORM CONTENT -->
<section class="section">
    <h2>Add Donation</h2>

    <div class="auth-card" style="max-width: 600px; margin: 0 auto;">
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-utensils"></i>
                <input type="text" name="food_type" placeholder="Food Type" required>
            </div>

            <div class="input-group">
                <i class="fas fa-weight"></i>
                <input type="text" name="quantity" placeholder="Quantity" required>
            </div>

            <div class="input-group">
                <i class="fas fa-calendar"></i>
                <input type="date" name="expiry_date" required>
            </div>

            <div class="input-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Pickup Location" required>
            </div>

            <div class="input-group">
                <i class="fas fa-comment"></i>
                <textarea name="description" placeholder="Description" rows="3"></textarea>
            </div>

            <button type="submit" name="add_donation" class="btn primary">Add Donation</button>
        </form>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Donor Panel</p>
</footer>

<?php
if (isset($_POST['add_donation'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO donations (donor_id, food_type, quantity, expiry_date, location, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $_POST['food_type'], $_POST['quantity'], $_POST['expiry_date'], $_POST['location'], $_POST['description']]);
        echo "<script>alert('Donation added successfully!'); window.location='dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
</body>
</html>

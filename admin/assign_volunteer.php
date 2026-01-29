<?php include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Volunteer | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Admin</div>
    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- ASSIGNMENT CONTENT -->
<section class="section">
    <h2>Assign Volunteers</h2>
    <p>Match donations with requests and assign volunteers.</p>

    <div class="auth-card" style="max-width: 800px; margin: 0 auto;">
        <form method="POST">
            <div class="input-group">
                <i class="fas fa-hand-holding-heart"></i>
                <select name="donation_id" required>
                    <option value="">Select Donation</option>
                    <?php
                    $stmt = $conn->prepare("SELECT id, food_type, quantity FROM donations WHERE status = 'available'");
                    $stmt->execute();
                    $donations = $stmt->fetchAll();
                    foreach ($donations as $donation) {
                        echo "<option value='{$donation['id']}'>{$donation['food_type']} - {$donation['quantity']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-utensils"></i>
                <select name="request_id" required>
                    <option value="">Select Request</option>
                    <?php
                    $stmt = $conn->prepare("SELECT id, food_type, quantity_needed FROM food_requests WHERE status = 'pending'");
                    $stmt->execute();
                    $requests = $stmt->fetchAll();
                    foreach ($requests as $request) {
                        echo "<option value='{$request['id']}'>{$request['food_type']} - {$request['quantity_needed']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <i class="fas fa-user"></i>
                <select name="volunteer_id" required>
                    <option value="">Select Volunteer</option>
                    <?php
                    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'volunteer'");
                    $stmt->execute();
                    $volunteers = $stmt->fetchAll();
                    foreach ($volunteers as $volunteer) {
                        echo "<option value='{$volunteer['id']}'>{$volunteer['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" name="assign_volunteer" class="btn primary">Assign Volunteer</button>
        </form>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Admin Panel</p>
</footer>

<?php
if (isset($_POST['assign_volunteer'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO assignments (donation_id, request_id, volunteer_id) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['donation_id'], $_POST['request_id'], $_POST['volunteer_id']]);

        // Update statuses
        $conn->prepare("UPDATE donations SET status = 'assigned' WHERE id = ?")->execute([$_POST['donation_id']]);
        $conn->prepare("UPDATE food_requests SET status = 'assigned' WHERE id = ?")->execute([$_POST['request_id']]);

        echo "<script>alert('Volunteer assigned successfully!'); window.location='dashboard.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>
</body>
</html>

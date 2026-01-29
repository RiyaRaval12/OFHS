<?php 
include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Dashboard | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Donor</div>
    <div class="nav-links">
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- DASHBOARD CONTENT -->
<section class="section">
    <h2>Donor Dashboard</h2>
    <p>Welcome, Donor! Help reduce food waste by donating surplus food.</p>

    <div class="cards">
        <div class="card">
            <h3><i class="fas fa-plus"></i> Add Donation</h3>
            <p>Post surplus food for donation.</p>
            <a href="add_donation.php" class="btn primary">Add Donation</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-list"></i> My Donations</h3>
            <p>View your donation history.</p>
            <a href="#" class="btn primary">View Donations</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Donor Panel</p>
</footer>

</body>
</html>

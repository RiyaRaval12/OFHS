<?php include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Admin</div>
    <div class="nav-links">
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- DASHBOARD CONTENT -->
<section class="section">
    <h2>Admin Dashboard</h2>
    <p>Welcome, Admin! Manage the food helpline system.</p>

    <div class="cards">
        <div class="card">
            <h3><i class="fas fa-users"></i> Manage Users</h3>
            <p>View and manage all users.</p>
            <a href="manage_users.php" class="btn primary">Manage Users</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-handshake"></i> Assign Volunteers</h3>
            <p>Assign volunteers to donations and requests.</p>
            <a href="assign_volunteer.php" class="btn primary">Assign Volunteers</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-chart-bar"></i> View Reports</h3>
            <p>View system reports and statistics.</p>
            <a href="#" class="btn primary">View Reports</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Admin Panel</p>
</footer>

</body>
</html>

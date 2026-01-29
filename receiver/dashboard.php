<?php include("../includes/auth_check.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receiver Dashboard | Food Helpline</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline - Receiver</div>
    <div class="nav-links">
        <a href="../auth/logout.php">Logout</a>
    </div>
</nav>

<!-- DASHBOARD CONTENT -->
<section class="section">
    <h2>Receiver Dashboard</h2>
    <p>Welcome, Receiver! Request food assistance from donors.</p>

    <div class="cards">
        <div class="card">
            <h3><i class="fas fa-utensils"></i> Request Food</h3>
            <p>Submit a request for food assistance.</p>
            <a href="request_food.php" class="btn primary">Request Food</a>
        </div>

        <div class="card">
            <h3><i class="fas fa-list"></i> My Requests</h3>
            <p>View your food request history.</p>
            <a href="#" class="btn primary">View Requests</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Online Food Helpline System | Receiver Panel</p>
</footer>

</body>
</html>

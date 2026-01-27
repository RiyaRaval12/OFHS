<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Online Food Helpline</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline</div>
    <div class="nav-links">
        <a href="auth/login.php">Login</a>
        <a href="auth/register.php" class="btn primary">Register</a>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-text">
        <h1>No Food Waste.<br><span>No Hunger.</span></h1>
        <p>
            A digital platform connecting donors, NGOs and volunteers to ensure surplus food reaches those in need.
        </p>
        <div class="hero-buttons">
            <a href="auth/register.php" class="btn primary">Get Started</a>
            <a href="#how" class="btn secondary">How It Works</a>
        </div>
    </div>

    <div class="hero-image">
        <img src="./assets/images/food_hero.png" alt="Food Delivery">
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" class="section">
    <h2>How It Works</h2>

    <div class="cards">
        <div class="card">
            <h3><i class="fas fa-leaf"></i> Donors</h3>
            <p>Post surplus food with quantity, expiry, and location.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-hospital"></i> Receivers</h3>
            <p>NGOs request available food in real time.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-truck"></i> Volunteers</h3>
            <p>Pick up food and deliver it safely.</p>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>
        © <?php echo date("Y"); ?> Online Food Helpline System | Capstone Project
    </p>
</footer>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FoodHelpline | No Food Waste, No Hunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script defer src="assets/js/main.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="landing">

<!-- NAVBAR -->
<nav class="navbar landing-nav">
    <div class="logo"><i class="fas fa-utensils"></i> FoodHelpline</div>
    <div class="nav-links">
        <a href="#how">How it works</a>
        <a href="#impact">Impact</a>
        <a href="auth/login.php">Login</a>
        <a href="auth/register.php" class="btn primary">Join the network</a>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero landing-hero">
    <div class="hero-text">
        <div class="pill-badge"><i class="fas fa-leaf"></i> Zero waste, full plates</div>
        <h1>Rescue surplus food. <span>Fuel hope.</span></h1>
        <p>
            FoodHelpline connects donors, NGOs, and volunteers so good food never goes to waste.
            Track pickups, requests, and deliveries in one clean dashboard.
        </p>
        <div class="hero-buttons">
            <a href="auth/register.php" class="btn primary">Get Started</a>
            <a href="auth/login.php" class="btn ghost">I already have an account</a>
        </div>
        <div class="hero-stats">
            <div class="stat-chip"><span>2.1k+</span> meals rescued</div>
            <div class="stat-chip"><span>480</span> volunteers</div>
            <div class="stat-chip"><span>12</span> cities covered</div>
        </div>
    </div>

    <div class="hero-visual">
        <div class="floating-card">
            <div class="badge green">Fresh Pickup</div>
            <h3>15 loaves ready</h3>
            <p>123 Main St, Alice's Bakery</p>
            <div class="mini-row"><i class="far fa-clock"></i> Expires in 4 hrs</div>
            <div class="mini-row"><i class="fas fa-route"></i> Volunteer en route</div>
        </div>
        <div class="floating-card ghost">
            <div class="badge blue">Urgent Request</div>
            <h4>Family of 4 needs meals</h4>
            <p>456 Elm St · Needed today</p>
        </div>
        <img src="./assets/images/food_hero.png" alt="Food Delivery" class="hero-illustration">
    </div>
</section>

<!-- HOW IT WORKS -->
<section id="how" class="section how">
    <div class="section-head">
        <div>
            <p class="eyebrow">Simple, transparent, fast</p>
            <h2>How FoodHelpline flows</h2>
        </div>
        <a href="auth/register.php" class="link-cta">Join now <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="cards step-cards">
        <div class="card hoverable">
            <div class="step-icon">1</div>
            <h3>Donors list surplus</h3>
            <p>Share quantity, expiry, and pickup point in seconds.</p>
        </div>

        <div class="card hoverable">
            <div class="step-icon">2</div>
            <h3>Receivers request</h3>
            <p>NGOs and shelters claim what matches their needs.</p>
        </div>

        <div class="card hoverable">
            <div class="step-icon">3</div>
            <h3>Volunteers deliver</h3>
            <p>Pickup routes and delivery status tracked live.</p>
        </div>
    </div>
</section>

<!-- IMPACT -->
<section id="impact" class="section impact">
    <div class="section-head">
        <div>
            <p class="eyebrow">Our impact</p>
            <h2>Every listing creates a ripple</h2>
        </div>
        <a href="auth/login.php" class="link-cta">See the dashboard <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="cards impact-cards">
        <div class="card glass">
            <div class="impact-number">98%</div>
            <p>of posted food gets claimed before expiry.</p>
        </div>
        <div class="card glass">
            <div class="impact-number">4.7 / 5</div>
            <p>average satisfaction from community partners.</p>
        </div>
        <div class="card glass">
            <div class="impact-number">24 hrs</div>
            <p>average time from posting to delivery completion.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section callout">
    <div class="callout-card">
        <div>
            <p class="eyebrow">Ready to make a dent in food waste?</p>
            <h2>Join the FoodHelpline network today.</h2>
            <p>Whether you have surplus to share or hands to help, we’ve got a place for you.</p>
        </div>
        <div class="callout-actions">
            <a href="auth/register.php" class="btn primary">Create free account</a>
            <a href="auth/login.php" class="btn ghost">Log in</a>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>
        &copy; <?php echo date("Y"); ?> Online Food Helpline System | Capstone Project
    </p>
</footer>

</body>
</html>

<?php
$page_title = "Dashboard";
$active = "dashboard";
include("includes/header.php");
include("includes/helpers.php");

$stats = fetchStats($conn, $currentUser);
$recentListings = fetchRecentListings($conn, 4);
$urgentRequests = fetchUrgentRequests($conn, 3);
$profileIncomplete = empty($currentUser['profile_completed']);
?>

<section class="page">
    <div class="section-head" style="margin-bottom:8px;">
        <div>
            <p class="lead" style="margin:0;">Here's what's happening in your community today.</p>
            <h1>Welcome back, <?php echo safe($currentUser['name']); ?>!</h1>
        </div>
        <div class="cta">
            <?php if ($currentUser['role'] === 'receiver'): ?>
                <a class="btn ghost" href="requests.php#new-request"><i class="far fa-heart"></i> Request Help</a>
            <?php endif; ?>
            <?php if ($currentUser['role'] === 'donor'): ?>
                <a class="btn primary" href="food_listings.php#donate"><i class="fas fa-plus"></i> Donate Food</a>
            <?php elseif ($currentUser['role'] === 'volunteer'): ?>
                <a class="btn primary" href="activity.php"><i class="fas fa-route"></i> View Tasks</a>
            <?php else: ?>
                <a class="btn primary" href="food_listings.php"><i class="fas fa-seedling"></i> Explore Listings</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($profileIncomplete): ?>
        <div class="alert" style="margin-top:10px;">
            <i class="fas fa-info-circle"></i>
            <div>
                <strong>Complete your profile</strong><br>
                Please <a href="profile.php">update your profile</a> with contact details to start participating.
            </div>
        </div>
    <?php endif; ?>

    <div class="grid stats">
        <div class="card stat-card">
            <div class="label">Available Food</div>
            <div class="value"><?php echo $stats['available_food']; ?></div>
            <div class="meta"><i class="fas fa-bread-slice"></i> Listings near you</div>
        </div>
        <div class="card stat-card">
            <div class="label">Help Requests</div>
            <div class="value"><?php echo $stats['open_requests']; ?></div>
            <div class="meta"><i class="fas fa-hand-holding-heart"></i> People needing assistance</div>
        </div>
        <div class="card stat-card">
            <div class="label">My Impact</div>
            <div class="value"><?php echo $stats['my_impact']; ?></div>
            <div class="meta"><i class="fas fa-truck"></i> Deliveries completed</div>
        </div>
    </div>

    <div class="grid two">
        <div>
            <div class="section-head">
                <h3>Recent Food Listings</h3>
                <a href="food_listings.php">View All</a>
            </div>
            <?php if (count($recentListings) === 0): ?>
                <div class="empty">No active listings at the moment.</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($recentListings as $listing): ?>
                        <?php $badge = statusBadge($listing['status']); ?>
                        <div class="listing-card">
                            <div class="meta" style="justify-content: space-between;">
                                <span class="<?php echo $badge['class']; ?>"><?php echo $badge['label']; ?></span>
                                <span class="pill"><i class="fas fa-user"></i> By <?php echo safe($listing['donor_name']); ?></span>
                            </div>
                            <h4><?php echo safe($listing['title']); ?></h4>
                            <div class="meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo safe($listing['location']); ?></span>
                                <span><i class="far fa-clock"></i> Expires: <?php echo formatDate($listing['expires_at'], 'M j, g:i A'); ?></span>
                                <span>Quantity: <?php echo (int)$listing['quantity'] . ' ' . safe($listing['unit']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="section-head">
                <h3>Urgent Requests</h3>
                <a href="requests.php">View All</a>
            </div>
            <?php if (count($urgentRequests) === 0): ?>
                <div class="empty">No active requests at the moment.</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($urgentRequests as $req): ?>
                        <?php $badge = statusBadge($req['status']); ?>
                        <div class="request-card">
                            <div class="meta" style="justify-content: space-between;">
                                <span class="<?php echo $badge['class']; ?>"><?php echo $badge['label']; ?></span>
                                <span class="pill"><i class="fas fa-user"></i> <?php echo safe($req['requester_name']); ?></span>
                            </div>
                            <h4><?php echo safe($req['title']); ?></h4>
                            <p><?php echo safe($req['description']); ?></p>
                            <div class="meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo safe($req['address']); ?></span>
                                <?php if ($req['needed_by']): ?>
                                    <span><i class="far fa-clock"></i> Needed by <?php echo formatDate($req['needed_by'], 'M j, g:i A'); ?></span>
                                <?php endif; ?>
                                <span>Servings: <?php echo (int)$req['servings']; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>

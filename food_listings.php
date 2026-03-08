<?php
$page_title = "Food Listings";
$active = "listings";
include("includes/header.php");
include("includes/helpers.php");

$flash = null;
$error = null;

// Create listing (donor/admin)
if (isset($_POST['add_listing'])) {
    if (!in_array($currentUser['role'], ['donor', 'admin'])) {
        $error = "Only donors can add listings.";
    } else {
        $expiresAt = !empty($_POST['expires_at']) ? str_replace('T', ' ', $_POST['expires_at']) : null;
        $title = trim($_POST['title']);
        $desc = trim($_POST['description']);
        $qty = (int)$_POST['quantity'];
        $unit = trim($_POST['unit']);
        $location = trim($_POST['location']);

        if ($title === '' || $location === '') {
            $error = "Title and location are required.";
        } elseif ($qty <= 0) {
            $error = "Quantity must be a positive number.";
        } else {
            $stmt = $conn->prepare("INSERT INTO food_listings (user_id, title, description, quantity, unit, location, expires_at) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([
                $currentUser['id'],
                $title,
                $desc,
                $qty,
                $unit ?: 'items',
                $location,
                $expiresAt
            ]);
            $flash = "Food listing posted!";
        }
    }
}

// Claim a listing
if (isset($_POST['claim_listing'])) {
    $listingId = (int)$_POST['listing_id'];
    if (in_array($currentUser['role'], ['receiver', 'volunteer'])) {
        $claimedBy = $currentUser['role'] === 'receiver' ? $currentUser['id'] : null;
        $volunteer = $currentUser['role'] === 'volunteer' ? $currentUser['id'] : null;
        $stmt = $conn->prepare("UPDATE food_listings SET status='claimed', claimed_by = COALESCE(claimed_by, ?), volunteer_id = COALESCE(volunteer_id, ?) WHERE id=? AND status='available'");
        $stmt->execute([$claimedBy, $volunteer, $listingId]);
        if ($stmt->rowCount() > 0) {
            $flash = "Listing claimed successfully.";
        } else {
            $flash = "Listing is no longer available.";
        }
    } else {
        $flash = "Only receivers or volunteers can claim listings.";
    }
}

// Mark complete
if (isset($_POST['complete_listing'])) {
    $listingId = (int)$_POST['listing_id'];
    // Only donor who created it or assigned volunteer can complete
    $check = $conn->prepare("SELECT * FROM food_listings WHERE id=? LIMIT 1");
    $check->execute([$listingId]);
    $listing = $check->fetch(PDO::FETCH_ASSOC);
    if ($listing && ($listing['user_id'] == $currentUser['id'] || $listing['volunteer_id'] == $currentUser['id'] || $currentUser['role'] === 'admin')) {
        $stmt = $conn->prepare("UPDATE food_listings SET status='completed' WHERE id=?");
        $stmt->execute([$listingId]);
        $conn->prepare("INSERT INTO activity_log (user_id, activity, ref_type, ref_id) VALUES (?,?,?,?)")
            ->execute([$currentUser['id'], 'donation_completed', 'food_listing', $listingId]);
        $flash = "Marked as delivered.";
    } else {
        $flash = "You cannot complete this listing.";
    }
}

$listings = $conn->query("SELECT fl.*, 
    u.name AS donor_name, 
    rc.name AS receiver_name, 
    vol.name AS volunteer_name
    FROM food_listings fl
    LEFT JOIN users u ON u.id = fl.user_id
    LEFT JOIN users rc ON rc.id = fl.claimed_by
    LEFT JOIN users vol ON vol.id = fl.volunteer_id
    ORDER BY CASE WHEN fl.status='available' THEN 0 WHEN fl.status='claimed' THEN 1 ELSE 2 END, fl.expires_at ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="page">
    <div class="section-head" style="margin-bottom:6px;">
        <div>
            <p class="lead">Find or share available surplus food.</p>
            <h1>Food Listings</h1>
        </div>
        <?php if ($currentUser['role'] === 'donor' || $currentUser['role'] === 'admin'): ?>
            <a class="btn warning" href="#donate"><i class="fas fa-plus"></i> Donate Food</a>
        <?php endif; ?>
    </div>

    <?php if ($flash): ?>
        <div class="flash"><?php echo safe($flash); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flash" style="background:#ffe7e6;border-color:#ffcfcf;color:#c62828;"><?php echo safe($error); ?></div>
    <?php endif; ?>

    <div class="grid two">
        <div>
            <?php if (count($listings) === 0): ?>
                <div class="empty">No listings yet. <?php if ($currentUser['role']==='donor'): ?>Be the first to donate!<?php endif; ?></div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($listings as $listing): ?>
                        <?php $badge = statusBadge($listing['status']); ?>
                        <div class="listing-card">
                            <div class="meta" style="justify-content: space-between;">
                                <span class="<?php echo $badge['class']; ?>"><?php echo $badge['label']; ?></span>
                                <span class="pill"><i class="fas fa-user"></i> <?php echo safe($listing['donor_name']); ?></span>
                            </div>
                            <h4><?php echo safe($listing['title']); ?></h4>
                            <?php if ($listing['description']): ?>
                                <p class="meta" style="color:var(--gray-700);"><?php echo safe($listing['description']); ?></p>
                            <?php endif; ?>
                            <div class="meta">
                                <span><i class="fas fa-map-marker-alt"></i> <?php echo safe($listing['location']); ?></span>
                                <?php if ($listing['expires_at']): ?>
                                    <span><i class="far fa-clock"></i> Expires: <?php echo formatDate($listing['expires_at'], 'M j, g:i A'); ?></span>
                                <?php endif; ?>
                                <span>Quantity: <?php echo (int)$listing['quantity'] . ' ' . safe($listing['unit']); ?></span>
                            </div>
                            <?php if ($listing['claimed_by']): ?>
                                <div class="meta">
                                    <i class="fas fa-hand-holding-heart"></i> Claimed by <?php echo safe($listing['receiver_name']); ?>
                                    <?php if ($listing['volunteer_name']): ?> · Volunteer: <?php echo safe($listing['volunteer_name']); ?><?php endif; ?>
                                </div>
                            <?php elseif ($listing['volunteer_name']): ?>
                                <div class="meta"><i class="fas fa-route"></i> Volunteer: <?php echo safe($listing['volunteer_name']); ?></div>
                            <?php endif; ?>
                            <div class="actions">
                                <div></div>
                                <div style="display:flex; gap:8px;">
                                    <?php if ($listing['status'] === 'available' && in_array($currentUser['role'], ['receiver','volunteer'])): ?>
                                        <form method="POST">
                                            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                            <button type="submit" name="claim_listing" class="btn primary" <?php echo $listing['status']!=='available'?'disabled':''; ?>>Claim / Pickup</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if (in_array($listing['status'], ['claimed']) && ($listing['user_id']==$currentUser['id'] || $listing['volunteer_id']==$currentUser['id'] || $currentUser['role']==='admin')): ?>
                                        <form method="POST">
                                            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                                            <button type="submit" name="complete_listing" class="btn ghost">Mark Delivered</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($currentUser['role'] === 'donor' || $currentUser['role'] === 'admin'): ?>
            <div id="donate">
                <div class="form-card">
                    <h3 style="margin-bottom:12px;">Add a Food Listing</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" placeholder="Short notes (e.g. contents, allergens)"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <div style="display:flex; gap:8px;">
                            <input type="number" name="quantity" min="1" value="1" style="flex:1;" required>
                            <input type="text" name="unit" value="items" style="flex:1;" required>
                        </div>
                    </div>
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" required placeholder="Pickup address">
                        </div>
                        <div class="form-group">
                            <label>Expires At</label>
                            <input type="datetime-local" name="expires_at">
                        </div>
                        <button type="submit" name="add_listing" class="btn primary full"><i class="fas fa-plus"></i> Publish Listing</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include("includes/footer.php"); ?>

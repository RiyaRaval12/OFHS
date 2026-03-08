<?php
$page_title = "My Activity";
$active = "activity";
include("includes/header.php");
include("includes/helpers.php");

$flash = null;

// Mark delivered action (for volunteer)
if (isset($_POST['deliver_request'])) {
    $reqId = (int)$_POST['request_id'];
    $reqStmt = $conn->prepare("SELECT * FROM assistance_requests WHERE id=? LIMIT 1");
    $reqStmt->execute([$reqId]);
    $req = $reqStmt->fetch(PDO::FETCH_ASSOC);
    if ($req && ($req['volunteer_id'] == $currentUser['id'] || $currentUser['role']==='admin')) {
        $conn->prepare("UPDATE assistance_requests SET status='delivered' WHERE id=?")->execute([$reqId]);
        $conn->prepare("INSERT INTO activity_log (user_id, activity, ref_type, ref_id) VALUES (?,?,?,?)")
            ->execute([$currentUser['id'], 'delivery_completed', 'assistance_request', $reqId]);
        $flash = "Great job! Marked as delivered.";
    } else {
        $flash = "You cannot update this request.";
    }
}

$myRequests = $conn->prepare("SELECT ar.*, u.name AS requester_name FROM assistance_requests ar
    JOIN users u ON u.id = ar.requester_id
    WHERE ar.volunteer_id = ?
    ORDER BY CASE WHEN ar.status='picked_up' THEN 0 WHEN ar.status='open' THEN 1 ELSE 2 END, ar.created_at DESC");
$myRequests->execute([$currentUser['id']]);
$myRequests = $myRequests->fetchAll(PDO::FETCH_ASSOC);

$myDonations = [];
if ($currentUser['role'] === 'donor') {
    $donStmt = $conn->prepare("SELECT * FROM food_listings WHERE user_id = ? ORDER BY created_at DESC");
    $donStmt->execute([$currentUser['id']]);
    $myDonations = $donStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<section class="page">
    <div class="section-head" style="margin-bottom:8px;">
        <div>
            <p class="lead">Track your deliveries and donations.</p>
            <h1>My Activity</h1>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="flash"><?php echo safe($flash); ?></div>
    <?php endif; ?>

    <div class="grid two">
        <div>
            <div class="section-head">
                <h3>Assigned Requests</h3>
            </div>
            <?php if (count($myRequests) === 0): ?>
                <div class="empty">No assigned requests yet.</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($myRequests as $req): ?>
                        <?php $badge = statusBadge($req['status']); ?>
                        <div class="request-card">
                            <div class="meta" style="justify-content: space-between;">
                                <span class="<?php echo $badge['class']; ?>"><?php echo $badge['label']; ?></span>
                                <span class="pill"><i class="fas fa-user"></i> <?php echo safe($req['requester_name']); ?></span>
                            </div>
                            <h4>Request: <?php echo safe($req['title']); ?></h4>
                            <p>To: <?php echo safe($req['address']); ?></p>
                            <p><?php echo safe($req['description']); ?></p>
                            <div class="meta">
                                <span><i class="far fa-calendar-check"></i> <?php echo formatDate($req['created_at']); ?></span>
                            </div>
                            <?php if ($req['status'] === 'picked_up'): ?>
                                <form method="POST" class="actions" style="justify-content:flex-end;">
                                    <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                    <button type="submit" name="deliver_request" class="btn primary">Mark Delivered</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($currentUser['role'] === 'donor'): ?>
            <div>
                <div class="section-head">
                    <h3>My Donations</h3>
                </div>
                <?php if (count($myDonations) === 0): ?>
                    <div class="empty">No donations posted yet.</div>
                <?php else: ?>
                    <div class="grid cards">
                        <?php foreach ($myDonations as $don): ?>
                            <?php $badge = statusBadge($don['status']); ?>
                            <div class="listing-card">
                                <div class="meta" style="justify-content: space-between;">
                                    <span class="<?php echo $badge['class']; ?>"><?php echo $badge['label']; ?></span>
                                    <span class="pill"><i class="fas fa-map-marker-alt"></i> <?php echo safe($don['location']); ?></span>
                                </div>
                                <h4><?php echo safe($don['title']); ?></h4>
                                <p><?php echo safe($don['description']); ?></p>
                                <div class="meta">
                                    <span>Quantity: <?php echo (int)$don['quantity'] . ' ' . safe($don['unit']); ?></span>
                                    <?php if ($don['expires_at']): ?><span>Expires: <?php echo formatDate($don['expires_at'], 'M j, g:i A'); ?></span><?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include("includes/footer.php"); ?>

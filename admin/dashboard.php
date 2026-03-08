<?php
$page_title = "Admin";
$active = "admin";
include("../includes/header.php");
include("../includes/helpers.php");

$flash = null;

// Assign volunteer to a request
if (isset($_POST['assign_volunteer'])) {
    $reqId = (int)$_POST['request_id'];
    $volId = (int)$_POST['volunteer_id'];
    $stmt = $conn->prepare("UPDATE assistance_requests SET volunteer_id=?, status='picked_up' WHERE id=?");
    $stmt->execute([$volId, $reqId]);
    $flash = "Volunteer assigned.";
}

$counts = [
    'users' => (int)$conn->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'donors' => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='donor'")->fetchColumn(),
    'receivers' => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='receiver'")->fetchColumn(),
    'volunteers' => (int)$conn->query("SELECT COUNT(*) FROM users WHERE role='volunteer'")->fetchColumn(),
    'open_requests' => (int)$conn->query("SELECT COUNT(*) FROM assistance_requests WHERE status IN ('open','picked_up')")->fetchColumn(),
    'listings' => (int)$conn->query("SELECT COUNT(*) FROM food_listings")->fetchColumn()
];

$volunteers = $conn->query("SELECT id, name FROM users WHERE role='volunteer' ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$openRequests = $conn->query("SELECT ar.*, u.name AS requester_name, vol.name AS volunteer_name
    FROM assistance_requests ar
    JOIN users u ON u.id = ar.requester_id
    LEFT JOIN users vol ON vol.id = ar.volunteer_id
    WHERE ar.status IN ('open','picked_up')
    ORDER BY ar.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$users = $conn->query("SELECT name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="page">
    <div class="section-head" style="margin-bottom:8px;">
        <div>
            <p class="lead">Admin overview</p>
            <h1>Control Panel</h1>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="flash"><?php echo safe($flash); ?></div>
    <?php endif; ?>

    <div class="grid stats">
        <div class="card stat-card"><div class="label">Total Users</div><div class="value"><?php echo $counts['users']; ?></div></div>
        <div class="card stat-card"><div class="label">Donors</div><div class="value"><?php echo $counts['donors']; ?></div></div>
        <div class="card stat-card"><div class="label">Receivers</div><div class="value"><?php echo $counts['receivers']; ?></div></div>
        <div class="card stat-card"><div class="label">Volunteers</div><div class="value"><?php echo $counts['volunteers']; ?></div></div>
        <div class="card stat-card"><div class="label">Open Requests</div><div class="value"><?php echo $counts['open_requests']; ?></div></div>
        <div class="card stat-card"><div class="label">Listings</div><div class="value"><?php echo $counts['listings']; ?></div></div>
    </div>

    <div class="grid two">
        <div>
            <div class="section-head">
                <h3>Open Requests</h3>
            </div>
            <?php if (count($openRequests) === 0): ?>
                <div class="empty">No open requests.</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($openRequests as $req): ?>
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
                                <?php if ($req['needed_by']): ?><span><i class="far fa-clock"></i> <?php echo formatDate($req['needed_by'], 'M j, g:i A'); ?></span><?php endif; ?>
                            </div>
                            <form method="POST" class="actions" style="justify-content:flex-end;gap:10px;">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <select name="volunteer_id" required style="padding:10px;border-radius:8px;border:1px solid #dde4e2;">
                                    <option value="">Assign volunteer</option>
                                    <?php foreach ($volunteers as $vol): ?>
                                        <option value="<?php echo $vol['id']; ?>" <?php echo ($req['volunteer_id']==$vol['id'])?'selected':''; ?>>
                                            <?php echo safe($vol['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="assign_volunteer" class="btn primary">Save</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="section-head">
                <h3>Recent Users</h3>
            </div>
            <div class="card">
                <?php foreach ($users as $user): ?>
                    <div style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eef1f4;">
                        <div>
                            <strong><?php echo safe($user['name']); ?></strong><br>
                            <span style="color:var(--gray-500); font-size:14px;"><?php echo safe($user['email']); ?></span>
                        </div>
                        <div style="text-align:right;">
                            <span class="badge gray"><?php echo ucfirst($user['role']); ?></span><br>
                            <span style="color:var(--gray-500); font-size:12px;"><?php echo formatDate($user['created_at']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include("../includes/footer.php"); ?>

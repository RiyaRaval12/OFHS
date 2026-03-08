<?php
$page_title = "Requests";
$active = "requests";
include("includes/header.php");
include("includes/helpers.php");

$flash = null;
$error = null;

// Create request (receiver)
if (isset($_POST['create_request'])) {
    if ($currentUser['role'] !== 'receiver') {
        $error = "Only receivers can request assistance.";
    } else {
        $neededBy = !empty($_POST['needed_by']) ? str_replace('T', ' ', $_POST['needed_by']) : null;
        $title = trim($_POST['title']);
        $desc = trim($_POST['description']);
        $servings = (int)$_POST['servings'];
        $address = trim($_POST['address']);

        if ($title === '' || $desc === '' || $address === '') {
            $error = "Title, description, and address are required.";
        } elseif ($servings <= 0) {
            $error = "Servings must be a positive number.";
        } else {
            $stmt = $conn->prepare("INSERT INTO assistance_requests (requester_id, title, description, servings, address, needed_by) VALUES (?,?,?,?,?,?)");
            $stmt->execute([
                $currentUser['id'],
                $title,
                $desc,
                $servings,
                $address,
                $neededBy
            ]);
            $flash = "Request submitted. Volunteers will see it shortly.";
        }
    }
}

// Volunteer picks up
if (isset($_POST['pickup_request'])) {
    if ($currentUser['role'] !== 'volunteer' && $currentUser['role'] !== 'admin') {
        $flash = "Only volunteers can pick up requests.";
    } else {
        $reqId = (int)$_POST['request_id'];
        $stmt = $conn->prepare("UPDATE assistance_requests SET status='picked_up', volunteer_id=? WHERE id=? AND status='open'");
        $stmt->execute([$currentUser['id'], $reqId]);
        $flash = $stmt->rowCount() ? "Request assigned to you." : "Request is not open anymore.";
    }
}

// Mark delivered
if (isset($_POST['deliver_request'])) {
    $reqId = (int)$_POST['request_id'];
    $check = $conn->prepare("SELECT * FROM assistance_requests WHERE id=? LIMIT 1");
    $check->execute([$reqId]);
    $req = $check->fetch(PDO::FETCH_ASSOC);
    if ($req && ($req['volunteer_id'] == $currentUser['id'] || $currentUser['role'] === 'admin')) {
        $conn->prepare("UPDATE assistance_requests SET status='delivered' WHERE id=?")->execute([$reqId]);
        $conn->prepare("INSERT INTO activity_log (user_id, activity, ref_type, ref_id) VALUES (?,?,?,?)")
            ->execute([$currentUser['id'], 'delivery_completed', 'assistance_request', $reqId]);
        $flash = "Marked delivered. Nice work!";
    } else {
        $flash = "You cannot mark this request delivered.";
    }
}

$requests = $conn->query("SELECT ar.*, u.name AS requester_name, vol.name AS volunteer_name
    FROM assistance_requests ar
    JOIN users u ON u.id = ar.requester_id
    LEFT JOIN users vol ON vol.id = ar.volunteer_id
    ORDER BY CASE WHEN ar.status='open' THEN 0 WHEN ar.status='picked_up' THEN 1 ELSE 2 END, COALESCE(ar.needed_by, ar.created_at) ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="page">
    <div class="section-head" style="margin-bottom:8px;">
        <div>
            <p class="lead">Help community members in need.</p>
            <h1>Assistance Requests</h1>
        </div>
        <?php if ($currentUser['role'] === 'receiver'): ?>
            <a class="btn ghost" href="#new-request"><i class="far fa-heart"></i> Request Help</a>
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
            <?php if (count($requests) === 0): ?>
                <div class="empty">No open requests. Everyone is helped for now!</div>
            <?php else: ?>
                <div class="grid cards">
                    <?php foreach ($requests as $req): ?>
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
                            <?php if ($req['volunteer_name']): ?>
                                <div class="meta"><i class="fas fa-route"></i> Volunteer: <?php echo safe($req['volunteer_name']); ?></div>
                            <?php endif; ?>
                            <div class="actions">
                                <div></div>
                                <div style="display:flex; gap:8px;">
                                    <?php if ($req['status'] === 'open' && in_array($currentUser['role'], ['volunteer','admin'])): ?>
                                        <form method="POST">
                                            <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                            <button type="submit" name="pickup_request" class="btn primary">Pick Up</button>
                                        </form>
                                    <?php endif; ?>
                                    <?php if ($req['status'] === 'picked_up' && ($req['volunteer_id'] == $currentUser['id'] || $currentUser['role']==='admin')): ?>
                                        <form method="POST">
                                            <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                            <button type="submit" name="deliver_request" class="btn ghost">Mark Delivered</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($currentUser['role'] === 'receiver'): ?>
            <div id="new-request">
                <div class="form-card">
                    <h3 style="margin-bottom:12px;">Request Help</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" required placeholder="E.g. Need food for a family of 4">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" required placeholder="Add dietary needs, urgency, etc."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Servings Needed</label>
                            <input type="number" name="servings" min="1" value="1" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" required value="<?php echo safe($currentUser['address']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Needed By</label>
                            <input type="datetime-local" name="needed_by">
                        </div>
                        <button type="submit" name="create_request" class="btn primary full"><i class="far fa-heart"></i> Submit Request</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include("includes/footer.php"); ?>

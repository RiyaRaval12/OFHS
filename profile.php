<?php
$page_title = "Profile";
$active = "";
include("includes/header.php");
include("includes/helpers.php");

$flash = null;
$error = null;

if (isset($_POST['save_profile'])) {
    $phone = str_replace([' ', '-'], '', trim($_POST['phone']));
    $address = trim($_POST['address']);
    $org = trim($_POST['organization']);

    if (!is_numeric($phone)) {
        $error = "Phone must contain numbers only.";
    } elseif ($address === '') {
        $error = "Address is required.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET phone=?, address=?, organization=?, profile_completed=? WHERE id=?");
        $completed = (!empty($phone) && !empty($address)) ? 1 : 0;
        $stmt->execute([
            $phone,
            $address,
            $org,
            $completed,
            $currentUser['id']
        ]);

        // refresh user data
        $userStmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $userStmt->execute([$currentUser['id']]);
        $currentUser = $userStmt->fetch(PDO::FETCH_ASSOC);
        $flash = "Profile updated.";
    }
}
?>

<section class="page">
    <div class="section-head" style="margin-bottom:10px;">
        <div>
            <p class="lead">Keep your contact details current.</p>
            <h1>Profile Details</h1>
        </div>
    </div>

    <?php if ($flash): ?>
        <div class="flash"><?php echo safe($flash); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="flash" style="background:#ffe7e6;border-color:#ffcfcf;color:#c62828;"><?php echo safe($error); ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">
            <div class="form-group">
                <label>I want to join as</label>
                <select disabled>
                    <option><?php echo ucfirst($currentUser['role']); ?></option>
                </select>
                <small style="display:block;color:var(--gray-500);margin-top:6px;">Role is set during sign up. Contact admin to change.</small>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required value="<?php echo safe($currentUser['phone']); ?>" inputmode="numeric">
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" required value="<?php echo safe($currentUser['address']); ?>">
            </div>
            <div class="form-group">
                <label>Organization Name (Optional)</label>
                <input type="text" name="organization" value="<?php echo safe($currentUser['organization']); ?>">
            </div>
            <button type="submit" name="save_profile" class="btn primary full">Save Changes</button>
        </form>
    </div>
</section>

<?php include("includes/footer.php"); ?>

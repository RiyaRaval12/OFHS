<?php

function safe($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate($dateString, $format = 'M j, Y') {
    if (!$dateString) return '—';
    return date($format, strtotime($dateString));
}

function fetchStats(PDO $conn, array $user): array {
    $stats = [
        'available_food' => 0,
        'open_requests'  => 0,
        'my_impact'      => 0
    ];

    $stats['available_food'] = (int)$conn->query("SELECT COUNT(*) FROM food_listings WHERE status = 'available'")->fetchColumn();
    $stats['open_requests']  = (int)$conn->query("SELECT COUNT(*) FROM assistance_requests WHERE status IN ('open','picked_up')")->fetchColumn();

    if ($user['role'] === 'volunteer') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM assistance_requests WHERE status = 'delivered' AND volunteer_id = ?");
        $stmt->execute([$user['id']]);
        $stats['my_impact'] = (int)$stmt->fetchColumn();
    } elseif ($user['role'] === 'donor') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM food_listings WHERE status IN ('completed') AND user_id = ?");
        $stmt->execute([$user['id']]);
        $stats['my_impact'] = (int)$stmt->fetchColumn();
    } elseif ($user['role'] === 'receiver') {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM assistance_requests WHERE status = 'delivered' AND requester_id = ?");
        $stmt->execute([$user['id']]);
        $stats['my_impact'] = (int)$stmt->fetchColumn();
    }

    return $stats;
}

function fetchRecentListings(PDO $conn, int $limit = 5) {
    $stmt = $conn->prepare("SELECT fl.*, u.name AS donor_name FROM food_listings fl
        JOIN users u ON u.id = fl.user_id
        ORDER BY fl.created_at DESC
        LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function fetchUrgentRequests(PDO $conn, int $limit = 5) {
    $stmt = $conn->prepare("SELECT ar.*, u.name AS requester_name FROM assistance_requests ar
        JOIN users u ON u.id = ar.requester_id
        WHERE ar.status IN ('open','picked_up')
        ORDER BY COALESCE(ar.needed_by, ar.created_at) ASC
        LIMIT ?");
    $stmt->bindValue(1, $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function statusBadge(string $status): array {
    $map = [
        'available'  => ['label' => 'Available', 'class' => 'badge green'],
        'claimed'    => ['label' => 'Claimed', 'class' => 'badge blue'],
        'completed'  => ['label' => 'Completed', 'class' => 'badge gray'],
        'expired'    => ['label' => 'Expired', 'class' => 'badge gray'],
        'open'       => ['label' => 'Open', 'class' => 'badge blue'],
        'picked_up'  => ['label' => 'Picked Up', 'class' => 'badge amber'],
        'delivered'  => ['label' => 'Delivered', 'class' => 'badge green'],
        'closed'     => ['label' => 'Closed', 'class' => 'badge gray'],
    ];
    return $map[$status] ?? ['label' => ucfirst($status), 'class' => 'badge gray'];
}

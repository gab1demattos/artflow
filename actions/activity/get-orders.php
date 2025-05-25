<?php
// actions/activity/get-orders.php
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/session.php');

header('Content-Type: application/json');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

$db = Database::getInstance();

// Fetch orders placed by the user (as client)
$stmt = $db->prepare('SELECT e.id, e.status, e.requirements, e.date, s.id as service_id, s.title, s.price, s.delivery_time, u.name as seller_name, u.username as seller_username FROM Exchange e JOIN Service s ON e.service_id = s.id JOIN User u ON s.user_id = u.id WHERE e.client_id = ? ORDER BY e.id DESC');
$stmt->execute([$user['id']]);
$yourOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Add rated flag for each order
foreach ($yourOrders as &$order) {
    $stmtR = $db->prepare('SELECT 1 FROM Review WHERE user_id = ? AND exchange_id = ?');
    $stmtR->execute([$user['id'], $order['id']]);
    $order['rated'] = $stmtR->fetch() ? true : false;
}

// Fetch orders for the user's services (as freelancer)
$stmt2 = $db->prepare('SELECT e.id, e.status, e.requirements, e.date, s.id as service_id, s.title, s.price, s.delivery_time, u.name as buyer_name, u.username as buyer_username FROM Exchange e JOIN Service s ON e.service_id = s.id JOIN User u ON e.client_id = u.id WHERE s.user_id = ? ORDER BY e.id DESC');
$stmt2->execute([$user['id']]);
$ordersFromOthers = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Add rated flag for each order from others (if you want sellers to rate buyers, otherwise skip this)
foreach ($ordersFromOthers as &$order) {
    $stmtR = $db->prepare('SELECT 1 FROM Review WHERE user_id = ? AND exchange_id = ?');
    $stmtR->execute([$user['id'], $order['id']]);
    $order['rated'] = $stmtR->fetch() ? true : false;
}

// Return both sets
echo json_encode([
    'success' => true,
    'yourOrders' => $yourOrders,
    'ordersFromOthers' => $ordersFromOthers
]);

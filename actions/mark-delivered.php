<?php
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/session.php');

header('Content-Type: application/json');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit();
}

$order_id = intval($_POST['order_id'] ?? 0);
if (!$order_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing order_id']);
    exit();
}

$db = Database::getInstance();
// Only allow the freelancer (seller) to mark as delivered
$stmt = $db->prepare('SELECT freelancer_id FROM Exchange WHERE id = ?');
$stmt->execute([$order_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row || $row['freelancer_id'] != $user['id']) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Not authorized']);
    exit();
}

$stmt = $db->prepare('UPDATE Exchange SET status = ? WHERE id = ?');
$stmt->execute(['completed', $order_id]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to update order status']);
}

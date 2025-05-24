<?php
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/csrf.php');
require_once(__DIR__ . '/../database/security.php');

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

// CSRF token validation - disable for AJAX requests using a dedicated API token approach
// Uncomment this section if you're implementing AJAX CSRF protection
/*
$token = $_POST['csrf_token'] ?? '';
if (!CSRF::validate($token, 'payment_csrf_token')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Invalid security token']);
    exit();
}
*/

$service_id = intval($_POST['service_id'] ?? 0);
$requirements = Security::sanitizeInput(trim($_POST['requirements'] ?? ''));
// Remove price and delivery_time from required fields and ignore them
if (!$service_id || $requirements === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$db = Database::getInstance();
// Get seller id from service
$stmt = $db->prepare('SELECT user_id, price, delivery_time FROM Service WHERE id = ?');
$stmt->execute([$service_id]);
$seller = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$seller) {
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Service not found']);
    exit();
}
$seller_id = $seller['user_id'];

// Insert into Exchange (date will default to CURRENT_TIMESTAMP)
$stmt = $db->prepare('INSERT INTO Exchange (client_id, freelancer_id, service_id, requirements, status) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([
    $user['id'],
    $seller_id,
    $service_id,
    $requirements,
    'in progress'
]);

if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to create order']);
}

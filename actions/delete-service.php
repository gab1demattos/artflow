<?php
// POST: service_id
// Deletes service
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/service.class.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['service_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit();
}

$serviceId = (int)$_POST['service_id'];
try {
    $result = Service::deleteServiceById($serviceId);
    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete service']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete service']);
}

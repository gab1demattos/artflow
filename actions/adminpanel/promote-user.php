<?php
// POST: user_id
// Promotes user to admin
require_once(__DIR__ . '/../../database/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit();
}
$userId = (int)$_POST['user_id'];
require_once(__DIR__ . '/../../database/database.php');
$db = Database::getInstance();
try {
    $stmt = $db->prepare('UPDATE User SET user_type = ? WHERE id = ?');
    $stmt->execute(['admin', $userId]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to promote user']);
}

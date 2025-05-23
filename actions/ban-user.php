<?php
// POST: user_id
// Deletes user and all their services (irreversible)
require_once(__DIR__ . '/../database/session.php');
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
require_once(__DIR__ . '/../database/database.php');
$db = Database::getInstance();
try {
    // Delete all services by this user (cascades to ServiceSubcategory, etc.)
    $stmt = $db->prepare('DELETE FROM Service WHERE user_id = ?');
    $stmt->execute([$userId]);
    // Delete the user
    $stmt = $db->prepare('DELETE FROM User WHERE id = ?');
    $stmt->execute([$userId]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete user']);
}

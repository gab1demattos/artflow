<?php
// Returns JSON: { users: [...], services: [...], categories: [...] }
// Only accessible by admin
require_once(__DIR__ . '/../../database/session.php');
$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

require_once(__DIR__ . '/../../database/database.php');
$db = Database::getInstance();
$users = $db->query('SELECT COUNT(*) FROM User')->fetchColumn();
$services = $db->query('SELECT COUNT(*) FROM Service')->fetchColumn();
$categories = $db->query('SELECT COUNT(*) FROM Category')->fetchColumn();
echo json_encode([
    'users' => (int)$users,
    'services' => (int)$services,
    'categories' => (int)$categories
]);

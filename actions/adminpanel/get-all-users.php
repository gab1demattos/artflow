<?php
// Returns JSON: [ {id, name, username, email, user_type} ... ]
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/database.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;
if (!$user || $user['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

$db = Database::getInstance();
$stmt = $db->query('SELECT id, name, username, email, user_type FROM User');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($users);

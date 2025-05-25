<?php
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
$stmt = $db->query('SELECT Service.id, Service.title, User.username AS owner, Category.category_type AS category FROM Service JOIN User ON Service.user_id = User.id JOIN Category ON Service.category_id = Category.id');
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($services);

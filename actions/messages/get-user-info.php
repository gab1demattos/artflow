<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');

header('Content-Type: application/json');

// Check if user is logged in
$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if this is a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get user ID from query parameter
$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
}

// Get user information
$db = Database::getInstance();
$stmt = $db->prepare('SELECT id, username, profile_image FROM User WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    http_response_code(404);
    echo json_encode(['error' => 'User not found']);
    exit();
}

echo json_encode([
    'success' => true,
    'user' => $user
]);

<?php
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/message.class.php');

$session = Session::getInstance();
$user = $session->getUser() ?? null;

if (!$user) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'User not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

$requestData = json_decode(file_get_contents('php://input'), true);

if (!isset($requestData['other_user_id']) || !is_numeric($requestData['other_user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Invalid user ID']);
    exit();
}

$otherUserId = (int)$requestData['other_user_id'];

try {
    $result = Message::deleteConversation($user['id'], $otherUserId);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Failed to delete conversation']);
    }
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

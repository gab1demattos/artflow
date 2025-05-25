<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/message.class.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: application/json');

$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['receiver_id']) || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$receiver_id = (int)$data['receiver_id'];
$message_text = trim($data['message']);

if (empty($message_text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty']);
    exit();
}

$message = Message::sendMessage($currentUser['id'], $receiver_id, $message_text);

if ($message) {
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT username, profile_image FROM User WHERE id = ?');
    $stmt->execute([$currentUser['id']]);
    $sender = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute([$receiver_id]);
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    $message->sender_username = $sender['username'];
    $message->receiver_username = $receiver['username'];
    $message->sender_profile_image = $sender['profile_image'];

    $response = [
        'success' => true,
        'message' => $message->toArray()
    ];
    echo json_encode($response);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}

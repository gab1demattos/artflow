<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/message.class.php');

header('Content-Type: application/json');

// Check if user is logged in
$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['receiver_id']) || !isset($data['message'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$receiver_id = (int)$data['receiver_id'];
$message_text = trim($data['message']);

// Validate message text
if (empty($message_text)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message cannot be empty']);
    exit();
}

// Send the message
$message = Message::sendMessage($currentUser['id'], $receiver_id, $message_text);

if ($message) {
    // Get user data for display
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT username, profile_image FROM User WHERE id = ?');
    $stmt->execute([$currentUser['id']]);
    $sender = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute([$receiver_id]);
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    // Add user data to message
    $message->sender_username = $sender['username'];
    $message->receiver_username = $receiver['username'];
    $message->sender_profile_image = $sender['profile_image'];

    // Return the sent message
    echo json_encode([
        'success' => true,
        'message' => $message->toArray()
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message']);
}

<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/message.class.php');

// For debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Create a log file for debugging - DISABLED
// file_put_contents(__DIR__ . '/../debug-messages-send.log', "Send request received: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

header('Content-Type: application/json');

// Check if user is logged in
$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    http_response_code(401);
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Error: User not authenticated\n", FILE_APPEND);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Log the current user - DISABLED
// file_put_contents(__DIR__ . '/../debug-messages-send.log', "Current user: " . json_encode($currentUser) . "\n", FILE_APPEND);

// Check if this is a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Error: Invalid method " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
// file_put_contents(__DIR__ . '/../debug-messages-send.log', "Request data: " . json_encode($data) . "\n", FILE_APPEND);

if (!$data || !isset($data['receiver_id']) || !isset($data['message'])) {
    http_response_code(400);
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Error: Missing required fields\n", FILE_APPEND);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$receiver_id = (int)$data['receiver_id'];
$message_text = trim($data['message']);

// file_put_contents(__DIR__ . '/../debug-messages-send.log', "Receiver ID: $receiver_id, Message: $message_text\n", FILE_APPEND);

// Validate message text
if (empty($message_text)) {
    http_response_code(400);
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Error: Message cannot be empty\n", FILE_APPEND);
    echo json_encode(['error' => 'Message cannot be empty']);
    exit();
}

// Send the message
$message = Message::sendMessage($currentUser['id'], $receiver_id, $message_text);

if ($message) {
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Message sent successfully, ID: " . $message->id . "\n", FILE_APPEND);

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
    $response = [
        'success' => true,
        'message' => $message->toArray()
    ];
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Response: " . json_encode($response) . "\n", FILE_APPEND);
    echo json_encode($response);
} else {
    http_response_code(500);
    // file_put_contents(__DIR__ . '/../debug-messages-send.log', "Error: Failed to send message\n", FILE_APPEND);
    echo json_encode(['error' => 'Failed to send message']);
}

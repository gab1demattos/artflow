<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/classes/message.class.php');

// For debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Create a log file for debugging - DISABLED
// file_put_contents(__DIR__ . '/../debug-messages.log', "Request received: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

header('Content-Type: application/json');

// Check if user is logged in
$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    http_response_code(401);
    // file_put_contents(__DIR__ . '/../debug-messages.log', "Error: User not authenticated\n", FILE_APPEND);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Log the current user - DISABLED
// file_put_contents(__DIR__ . '/../debug-messages.log', "Current user: " . json_encode($currentUser) . "\n", FILE_APPEND);

// Check if this is a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    // file_put_contents(__DIR__ . '/../debug-messages.log', "Error: Invalid method " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get the other user ID from the query string
$other_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// file_put_contents(__DIR__ . '/../debug-messages.log', "Other user ID: " . $other_user_id . "\n", FILE_APPEND);

if ($other_user_id <= 0) {
    http_response_code(400);
    // file_put_contents(__DIR__ . '/../debug-messages.log', "Error: Invalid user ID\n", FILE_APPEND);
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
}

// Get messages between current user and the other user
$messages = Message::getMessagesBetweenUsers($currentUser['id'], $other_user_id);
// file_put_contents(__DIR__ . '/../debug-messages.log', "Messages found: " . count($messages) . "\n", FILE_APPEND);

// Convert messages to array for JSON output
$messagesArray = array_map(function ($message) {
    return $message->toArray();
}, $messages);

// file_put_contents(__DIR__ . '/../debug-messages.log', "Response: " . json_encode(['success' => true, 'count' => count($messagesArray)]) . "\n", FILE_APPEND);

// Send response with messages
echo json_encode([
    'success' => true,
    'messages' => $messagesArray
]);

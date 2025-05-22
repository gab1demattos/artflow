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

// Check if this is a GET request
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get the other user ID from the query string
$other_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

if ($other_user_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
}

// Get messages between current user and the other user
$messages = Message::getMessagesBetweenUsers($currentUser['id'], $other_user_id);

// Convert messages to array for JSON output
$messagesArray = array_map(function ($message) {
    return $message->toArray();
}, $messages);

echo json_encode([
    'success' => true,
    'messages' => $messagesArray
]);

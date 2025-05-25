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

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$other_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;


if ($other_user_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
}

$messages = Message::getMessagesBetweenUsers($currentUser['id'], $other_user_id);

$messagesArray = array_map(function ($message) {
    return $message->toArray();
}, $messages);


echo json_encode([
    'success' => true,
    'messages' => $messagesArray
]);

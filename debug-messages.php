<?php
// Debug file to check message functionality
require_once(__DIR__ . '/database/database.php');
require_once(__DIR__ . '/database/classes/message.class.php');
require_once(__DIR__ . '/database/session.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the session
$session = Session::getInstance();
$user = $session->getUser();

if (!$user) {
    echo "Not logged in - please log in first";
    exit;
}

// Check if any messages exist in the database
$db = Database::getInstance();
$stmt = $db->prepare('SELECT COUNT(*) FROM Message');
$stmt->execute();
$messageCount = $stmt->fetchColumn();

echo "<h1>Message System Debug</h1>";
echo "<h2>Current User</h2>";
echo "<pre>" . print_r($user, true) . "</pre>";

echo "<h2>Message Count</h2>";
echo "<p>Total messages in database: $messageCount</p>";

// Check database structure
echo "<h2>Database Table Structure</h2>";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
echo "<ul>";
foreach ($tables as $table) {
    echo "<li><strong>{$table['name']}</strong><br>";
    $columns = $db->query("PRAGMA table_info({$table['name']})");
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>{$column['name']} ({$column['type']})</li>";
    }
    echo "</ul></li>";
}
echo "</ul>";

// Test sending a message to yourself
echo "<h2>Test: Send a message to yourself</h2>";
try {
    $message = Message::sendMessage($user['id'], $user['id'], "Test message: " . date('Y-m-d H:i:s'));
    if ($message) {
        echo "<p>Message sent successfully: ID {$message->id}</p>";
    } else {
        echo "<p>Failed to send test message</p>";
    }
} catch (Exception $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Fetch conversations
echo "<h2>User Conversations</h2>";
try {
    $conversations = Message::getConversationsForUser($user['id']);
    if (count($conversations) > 0) {
        echo "<ul>";
        foreach ($conversations as $convo) {
            $otherUserId = ($convo['sender_id'] == $user['id']) ? $convo['receiver_id'] : $convo['sender_id'];
            echo "<li>Conversation with User ID: {$otherUserId} - Latest message: {$convo['message']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No conversations found</p>";
    }
} catch (Exception $e) {
    echo "<p>Error fetching conversations: " . $e->getMessage() . "</p>";
}

// Check file paths for message actions
echo "<h2>Message Action Files</h2>";
$files = [
    '/actions/messages/get-messages.php',
    '/actions/messages/send-message.php',
    '/actions/messages/delete-conversation.php',
    '/actions/messages/get-user-info.php'
];

foreach ($files as $file) {
    $path = __DIR__ . $file;
    if (file_exists($path)) {
        echo "<p>✅ $file exists</p>";
    } else {
        echo "<p>❌ $file does not exist</p>";
    }
}

// Show JavaScript file paths
echo "<h2>JavaScript URLs</h2>";
$jsContent = file_get_contents(__DIR__ . '/js/messages.js');
preg_match_all('/fetch\([\'"]([^\'"]+)[\'"]/', $jsContent, $matches);

echo "<ul>";
foreach ($matches[1] as $url) {
    echo "<li>$url</li>";
}
echo "</ul>";

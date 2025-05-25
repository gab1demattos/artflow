<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database.php');
require_once(__DIR__ . '/../classes/user.class.php');

class Message
{
    public int $id;
    public int $sender_id;
    public int $receiver_id;
    public string $message;
    public string $timestamp;
    public ?string $sender_username = null;
    public ?string $receiver_username = null;
    public ?string $sender_profile_image = null;

  
    public function __construct(
        int $id,
        int $sender_id,
        int $receiver_id,
        string $message,
        string $timestamp,
        ?string $sender_username = null,
        ?string $receiver_username = null,
        ?string $sender_profile_image = null
    ) {
        $this->id = $id;
        $this->sender_id = $sender_id;
        $this->receiver_id = $receiver_id;
        $this->message = $message;
        $this->timestamp = $timestamp;
        $this->sender_username = $sender_username;
        $this->receiver_username = $receiver_username;
        $this->sender_profile_image = $sender_profile_image;
    }

    public static function sendMessage(int $sender_id, int $receiver_id, string $message): ?Message
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE id IN (?, ?)');
            $stmt->execute([$sender_id, $receiver_id]);
            $count = (int)$stmt->fetchColumn();

            if ($count < 2) {
                error_log("Cannot send message: one or both users don't exist");
                return null;
            }

            $stmt = $db->prepare('INSERT INTO Message (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, datetime("now", "localtime"))');
            $success = $stmt->execute([$sender_id, $receiver_id, $message]);

            if ($success) {
                $id = (int)$db->lastInsertId();

                $stmt = $db->prepare('SELECT timestamp FROM Message WHERE id = ?');
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $timestamp = $result['timestamp'] ?? date('Y-m-d H:i:s');

                return new Message($id, $sender_id, $receiver_id, $message, $timestamp);
            }

            error_log("Failed to insert message to database");
            return null;
        } catch (PDOException $e) {
            error_log("Database error in sendMessage: " . $e->getMessage());
            return null;
        }
    }


    public static function getMessagesBetweenUsers(int $user1_id, int $user2_id): array
    {
        $db = Database::getInstance();

        try {
            $testQuery = "SELECT COUNT(*) FROM Message WHERE (sender_id = $user1_id AND receiver_id = $user2_id) OR (sender_id = $user2_id AND receiver_id = $user1_id)";
            $result = $db->query($testQuery);
            $count = $result->fetchColumn();

            $stmt = $db->prepare('
                SELECT m.*, 
                    sender.username as sender_username, 
                    receiver.username as receiver_username,
                    sender.profile_image as sender_profile_image
                FROM Message m
                JOIN User sender ON m.sender_id = sender.id
                JOIN User receiver ON m.receiver_id = receiver.id
                WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
                ORDER BY m.timestamp ASC
            ');
            $stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);


            if (empty($messages)) {
                $stmt = $db->prepare('
                    SELECT * FROM Message 
                    WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
                    ORDER BY timestamp ASC
                ');
                $stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
                $basicMessages = $stmt->fetchAll(PDO::FETCH_ASSOC);


                if (!empty($basicMessages)) {
                    $userStmt = $db->prepare('SELECT id, username, profile_image FROM User WHERE id IN (?, ?)');
                    $userStmt->execute([$user1_id, $user2_id]);
                    $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

                    $userMap = [];
                    foreach ($users as $user) {
                        $userMap[$user['id']] = $user;
                    }

                    $result = [];
                    foreach ($basicMessages as $message) {
                        $sender = isset($userMap[$message['sender_id']]) ? $userMap[$message['sender_id']] : ['username' => 'Unknown', 'profile_image' => null];
                        $receiver = isset($userMap[$message['receiver_id']]) ? $userMap[$message['receiver_id']] : ['username' => 'Unknown', 'profile_image' => null];

                        $messageObj = new Message(
                            (int)$message['id'],
                            (int)$message['sender_id'],
                            (int)$message['receiver_id'],
                            $message['message'],
                            $message['timestamp'],
                            $sender['username'],
                            $receiver['username'],
                            $sender['profile_image']
                        );
                        $result[] = $messageObj;
                    }

                    return $result;
                }
            }

            $result = [];
            foreach ($messages as $message) {
                $messageObj = new Message(
                    (int)$message['id'],
                    (int)$message['sender_id'],
                    (int)$message['receiver_id'],
                    $message['message'],
                    $message['timestamp'],
                    $message['sender_username'],
                    $message['receiver_username'],
                    $message['sender_profile_image']
                );
                $result[] = $messageObj;
            }

            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }


    public static function getConversationsForUser(int $user_id): array
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('
                SELECT DISTINCT
                    CASE 
                        WHEN sender_id = ? THEN receiver_id
                        ELSE sender_id
                    END as other_user_id
                FROM Message
                WHERE sender_id = ? OR receiver_id = ?
            ');
            $stmt->execute([$user_id, $user_id, $user_id]);
            $otherUsers = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $conversations = [];

            foreach ($otherUsers as $otherUserId) {
                $userStmt = $db->prepare('SELECT username, profile_image FROM User WHERE id = ?');
                $userStmt->execute([$otherUserId]);
                $otherUser = $userStmt->fetch(PDO::FETCH_ASSOC);

                if (!$otherUser) {
                    continue; 
                }

                $msgStmt = $db->prepare('
                    SELECT 
                        m.id, m.sender_id, m.receiver_id, m.message, m.timestamp
                    FROM Message m
                    WHERE ((m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?))
                    ORDER BY m.timestamp DESC, m.id DESC
                    LIMIT 1
                ');
                $msgStmt->execute([$user_id, $otherUserId, $otherUserId, $user_id]);
                $lastMessage = $msgStmt->fetch(PDO::FETCH_ASSOC);

                if ($lastMessage) {
                    $lastMessage['other_username'] = $otherUser['username'];
                    $lastMessage['other_profile_image'] = $otherUser['profile_image'];

                    $conversations[] = $lastMessage;
                }
            }

            usort($conversations, function ($a, $b) {
                return strtotime($b['timestamp']) - strtotime($a['timestamp']);
            });

            return $conversations;
        } catch (PDOException $e) {
            error_log("Database error in getConversationsForUser: " . $e->getMessage());
            return [];
        }
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'message' => $this->message,
            'timestamp' => $this->timestamp,
            'sender_username' => $this->sender_username,
            'receiver_username' => $this->receiver_username,
            'sender_profile_image' => $this->sender_profile_image
        ];
    }


    public static function deleteConversation(int $user1_id, int $user2_id): bool
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('
                DELETE FROM Message
                WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?)
            ');

            return $stmt->execute([$user1_id, $user2_id, $user2_id, $user1_id]);
        } catch (PDOException $e) {
            error_log("Database error in deleteConversation: " . $e->getMessage());
            return false;
        }
    }
}

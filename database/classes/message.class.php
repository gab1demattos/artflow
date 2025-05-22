<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database.php');
require_once(__DIR__ . '/../user.class.php');

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

    /**
     * Constructor for Message
     */
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

    /**
     * Send a new message
     * 
     * @param int $sender_id Sender user ID
     * @param int $receiver_id Receiver user ID
     * @param string $message Message text
     * @return Message|null The sent message or null if failed
     */
    public static function sendMessage(int $sender_id, int $receiver_id, string $message): ?Message
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('INSERT INTO Message (sender_id, receiver_id, message, timestamp) VALUES (?, ?, ?, datetime("now"))');
            $success = $stmt->execute([$sender_id, $receiver_id, $message]);

            if ($success) {
                $id = (int)$db->lastInsertId();

                // Get timestamp of the inserted message
                $stmt = $db->prepare('SELECT timestamp FROM Message WHERE id = ?');
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $timestamp = $result['timestamp'];

                return new Message($id, $sender_id, $receiver_id, $message, $timestamp);
            }

            return null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * Get messages between two users
     * 
     * @param int $user1_id First user ID
     * @param int $user2_id Second user ID
     * @return array Array of Message objects
     */
    public static function getMessagesBetweenUsers(int $user1_id, int $user2_id): array
    {
        $db = Database::getInstance();
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
    }

    /**
     * Get conversations for a user
     * 
     * @param int $user_id User ID
     * @return array Array of conversation summaries
     */
    public static function getConversationsForUser(int $user_id): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            WITH LastMessages AS (
                SELECT 
                    m1.*,
                    ROW_NUMBER() OVER (
                        PARTITION BY 
                            CASE 
                                WHEN m1.sender_id = ? THEN m1.receiver_id 
                                ELSE m1.sender_id 
                            END
                        ORDER BY m1.timestamp DESC
                    ) as rn
                FROM Message m1
                WHERE m1.sender_id = ? OR m1.receiver_id = ?
            )
            SELECT 
                lm.id, lm.sender_id, lm.receiver_id, lm.message, lm.timestamp,
                CASE 
                    WHEN lm.sender_id = ? THEN receiver.username
                    ELSE sender.username
                END as other_username,
                CASE 
                    WHEN lm.sender_id = ? THEN receiver.profile_image
                    ELSE sender.profile_image
                END as other_profile_image
            FROM LastMessages lm
            JOIN User sender ON lm.sender_id = sender.id
            JOIN User receiver ON lm.receiver_id = receiver.id
            WHERE lm.rn = 1
            ORDER BY lm.timestamp DESC
        ');
        $stmt->execute([$user_id, $user_id, $user_id, $user_id, $user_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Convert to array for JSON output
     * 
     * @return array Message data as array
     */
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
}

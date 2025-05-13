<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../includes/database.php');

    class User {
        public int $id;
        public string $user_type;
        public string $name;
        public string $username;
        public string $email;

        public function __construct(int $id, string $user_type, string $name, string $username, string $email) {
            $this->id = $id;
            $this->user_type = $user_type;
            $this->name = $name;
            $this->username = $username;
            $this->email = $email;
        }

        public static function create(
            string $user_type = 'regular', 
            string $name, 
            string $username, 
            string $email, 
            string $password
        ) {
            $db = Database::getInstance();
            
            try {
                $stmt = $db->prepare('INSERT INTO User (user_type, name, username, email, password) VALUES (?, ?, ?, ?, ?)');
                $success = $stmt->execute([$user_type, $name, $username, $email, sha1($password)]);
                
                if ($success) {
                    $id = $db->lastInsertId();
                    return new User((int)$id, $user_type, $name, $username, $email);
                } else {
                    return false;
                }
            } catch (PDOException $e) {
                return false;
            }
        }

        public static function get_user_by_username_password($username, $password) {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM User WHERE username = ? AND password = ?');
            $stmt->execute([$username, sha1($password)]);

            return $stmt->fetch();
        }

        public static function get_user_by_email_password($email, $password) {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM User WHERE email = ? AND password = ?');
            $stmt->execute([$email, sha1($password)]);

            return $stmt->fetch();
        }
    }
?>
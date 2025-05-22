<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/database.php');

    class User {
        public int $id;
        public string $user_type;
        public string $name;
        public string $username;
        public string $email;
        public string $bio;
        public string $profile_image;

        public function __construct(int $id, string $user_type, string $name, string $username, string $email, string $bio, string $profile_image) {
            $this->id = $id;
            $this->user_type = $user_type;
            $this->name = $name;
            $this->username = $username;
            $this->email = $email;
            $this->bio = $bio;
            $this->profile_image = $profile_image;
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
                $stmt = $db->prepare('INSERT INTO User (user_type, name, username, email, password, bio, profile_image) VALUES (?, ?, ?, ?, ?, NULL, NULL)');
                $success = $stmt->execute([$user_type, $name, $username, $email, sha1($password)]);
                
                if ($success) {
                    $id = $db->lastInsertId();
                    return new User((int)$id, $user_type, $name, $username, $email, '', '');
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

        public static function get_user_by_username($username) {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM User WHERE username = ?');
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            if ($user) {
                return new User(
                    (int)$user['id'],
                    $user['user_type'],
                    $user['name'],
                    $user['username'],
                    $user['email'],
                    $user['bio'] ?? '',
                    $user['profile_image'] ?? ''
                );
            }
        }

        public function getId() {
            return $this->id;
        }

        public function getUserType() {
            return $this->user_type;
        }

        public function getName() {
            return $this->name;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getBio() {
            return $this->bio;
        }

        public function getProfileImage() {
            return $this->profile_image;
        }

        static function searchUsers(PDO $db, string $search, int $count) : array {
            $stmt = $db->prepare('SELECT id, name FROM User WHERE name LIKE ? LIMIT ?');
            $stmt->execute(array($search . '%', $count));
        
            $users = array();
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                $user['id'],
                $user['name']
              );
            }
        
            return $users;
          }
    }
?>
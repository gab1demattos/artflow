<?php
    declare(strict_types=1);
    require_once(__DIR__ . '/../includes/database.php');

    class User {
        public int $id;
        public string $user_type;
        //public int $isClient;
        //public int $isFreelancer;
        public string $name;
        public string $username;
        public string $email;

        public function __construct(int $id, string $user_type, string $name, string $username, string $email) {
            $this->id = $id;
            $this->user_type = $user_type;
            // $this->isClient = $isClient;
            // $this->isFreelancer = $isFreelancer;
            $this->name = $name;
            $this->username = $username;
            $this->email = $email;
        }

        public static function create(
            string $user_type = 'regular', 
            // int $isClient = 0, 
            // int $isFreelancer = 0, 
            string $name, 
            string $username, 
            string $email, 
            string $password
        ) {
            $db = Database::getInstance();
            $stmt = $db->prepare('INSERT INTO User (user_type, name, username, email, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$user_type, $name, $username, $email, sha1($password)]);
        }

        public static function get_user_by_username_password($username, $password) {
            $db = Database::getInstance();
            $stmt = $db->prepare('SELECT * FROM User WHERE username = ? AND password = ?');
            $stmt->execute([$username, sha1($password)]);

            return $stmt->fetch();
        }
    }
?>
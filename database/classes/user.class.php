<?php

declare(strict_types=1);
require_once(__DIR__ . '/../database.php');

class User
{
    public int $id;
    public string $user_type;
    public string $name;
    public string $username;
    public string $email;
    public string $bio;
    public string $profile_image;

    public function __construct(int $id, string $user_type, string $name, string $username, string $email, string $bio, string $profile_image)
    {
        $this->id = $id;
        $this->user_type = $user_type;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->bio = $bio;
        $this->profile_image = $profile_image;
    }

    public static function create(
        string $name,
        string $username,
        string $email,
        string $password,
        string $user_type = 'regular'
    ) {
        $db = Database::getInstance();

        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare('INSERT INTO User (user_type, name, username, email, password, bio, profile_image) VALUES (?, ?, ?, ?, ?, NULL, NULL)');
            $success = $stmt->execute([$user_type, $name, $username, $email, $passwordHash]);

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

    public static function get_user_by_username_password($username, $password)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM User WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        $stmt = $db->prepare('SELECT * FROM User WHERE username = ? AND password = ?');
        $stmt->execute([$username, sha1($password)]);
        $user = $stmt->fetch();

        if ($user) {
            self::updatePassword($user['id'], $password);
        }

        return $user;
    }

    public static function get_user_by_email_password($email, $password)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        $stmt = $db->prepare('SELECT * FROM User WHERE email = ? AND password = ?');
        $stmt->execute([$email, sha1($password)]);
        $user = $stmt->fetch();

        if ($user) {
            self::updatePassword($user['id'], $password);
        }

        return $user;
    }

    public static function get_user_by_username($username)
    {
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

    public static function get_user_by_id($id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
        $stmt->execute([$id]);
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
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserType()
    {
        return $this->user_type;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function getProfileImage()
    {
        return $this->profile_image;
    }

    public static function updatePassword(int $userId, string $password): bool
    {
        $db = Database::getInstance();

        try {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $db->prepare('UPDATE User SET password = ? WHERE id = ?');
            return $stmt->execute([$passwordHash, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function deleteAccount(int $userId): bool
    {
        $db = Database::getInstance();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare('DELETE FROM Review WHERE user_id = ?');
            $stmt->execute([$userId]);

            $stmt = $db->prepare('DELETE FROM Message WHERE sender_id = ? OR receiver_id = ?');
            $stmt->execute([$userId, $userId]);

            $stmt = $db->prepare('SELECT id FROM Service WHERE user_id = ?');
            $stmt->execute([$userId]);
            $services = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($services as $serviceId) {
                $stmt = $db->prepare('DELETE FROM ServiceSubcategory WHERE service_id = ?');
                $stmt->execute([$serviceId]);

                $stmt = $db->prepare('DELETE FROM Review WHERE service_id = ?');
                $stmt->execute([$serviceId]);
            }

            $stmt = $db->prepare('DELETE FROM Exchange WHERE client_id = ? OR service_id IN (SELECT id FROM Service WHERE user_id = ?)');
            $stmt->execute([$userId, $userId]);

            $stmt = $db->prepare('DELETE FROM Service WHERE user_id = ?');
            $stmt->execute([$userId]);

            $stmt = $db->prepare('DELETE FROM User WHERE id = ?');
            $stmt->execute([$userId]);

            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Error deleting account: " . $e->getMessage());
            return false;
        }
    }
    static function searchUsers(PDO $db, string $search, int $count): array
    {
        $stmt = $db->prepare('SELECT id, name, username, email, bio, profile_image FROM User WHERE name LIKE ? LIMIT ?');
        $stmt->execute(array($search . '%', $count));

        $users = array();
        while ($user = $stmt->fetch()) {
            $users[] = new User(
                (int)$user['id'],
                'regular', 
                $user['name'],
                $user['username'],
                $user['email'],
                $user['bio'] ?? '',
                $user['profile_image'] ?? ''
            );
        }

        return $users;
    }

    public static function getAllUsers(PDO $db): array
    {
        $stmt = $db->prepare('SELECT id, name, username, email, bio, profile_image FROM User');
        $stmt->execute();

        $users = array();
        while ($user = $stmt->fetch()) {
            $users[] = new User(
                (int)$user['id'],
                'regular', 
                $user['name'],
                $user['username'],
                $user['email'],
                $user['bio'] ?? '',
                $user['profile_image'] ?? ''
            );
        }

        return $users;
    }
}

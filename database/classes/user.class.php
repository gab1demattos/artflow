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

    public static function get_user_by_username_password($username, $password)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM User WHERE username = ? AND password = ?');
        $stmt->execute([$username, sha1($password)]);

        return $stmt->fetch();
    }

    public static function get_user_by_email_password($email, $password)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM User WHERE email = ? AND password = ?');
        $stmt->execute([$email, sha1($password)]);

        return $stmt->fetch();
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

    /**
     * Update user password
     *
     * @param int $userId The user ID
     * @param string $hashedPassword The hashed password
     * @return bool True if successful, false otherwise
     */
    public static function updatePassword(int $userId, string $hashedPassword): bool
    {
        $db = Database::getInstance();

        try {
            $stmt = $db->prepare('UPDATE User SET password = ? WHERE id = ?');
            return $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Delete a user account and all associated data
     *
     * @param int $userId The user ID to delete
     * @return bool True if successful, false otherwise
     */
    public static function deleteAccount(int $userId): bool
    {
        $db = Database::getInstance();

        try {
            // Start a transaction to ensure data integrity
            $db->beginTransaction();

            // 1. Delete user's reviews
            $stmt = $db->prepare('DELETE FROM Review WHERE user_id = ?');
            $stmt->execute([$userId]);

            // 2. Delete messages sent or received by the user
            $stmt = $db->prepare('DELETE FROM Message WHERE sender_id = ? OR receiver_id = ?');
            $stmt->execute([$userId, $userId]);

            // 3. Get services by this user
            $stmt = $db->prepare('SELECT id FROM Service WHERE user_id = ?');
            $stmt->execute([$userId]);
            $services = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // 4. For each service, delete related records
            foreach ($services as $serviceId) {
                // Delete service subcategories
                $stmt = $db->prepare('DELETE FROM ServiceSubcategory WHERE service_id = ?');
                $stmt->execute([$serviceId]);

                // Delete reviews for the service
                $stmt = $db->prepare('DELETE FROM Review WHERE service_id = ?');
                $stmt->execute([$serviceId]);
            }

            // 5. Delete exchanges involving the user
            $stmt = $db->prepare('DELETE FROM Exchange WHERE freelancer_id = ? OR client_id = ?');
            $stmt->execute([$userId, $userId]);

            // 6. Delete the user's services
            $stmt = $db->prepare('DELETE FROM Service WHERE user_id = ?');
            $stmt->execute([$userId]);

            // 7. Finally, delete the user
            $stmt = $db->prepare('DELETE FROM User WHERE id = ?');
            $stmt->execute([$userId]);

            // Commit the transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            // Rollback the transaction if something failed
            $db->rollBack();
            error_log("Error deleting account: " . $e->getMessage());
            return false;
        }
    }
    static function searchUsers(PDO $db, string $search, int $count) : array {
            $stmt = $db->prepare('SELECT id, name, username, email, bio, profile_image FROM User WHERE name LIKE ? LIMIT ?');
            $stmt->execute(array($search . '%', $count));

            $users = array();
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                    (int)$user['id'],
                    'regular', // Default user type
                    $user['name'],
                    $user['username'],
                    $user['email'],
                    $user['bio'] ?? '',
                    $user['profile_image'] ?? ''
                );
            }

            return $users;
        }

        public static function getAllUsers(PDO $db) : array {
            $stmt = $db->prepare('SELECT id, name, username, email, bio, profile_image FROM User');
            $stmt->execute();

            $users = array();
            while ($user = $stmt->fetch()) {
                $users[] = new User(
                    (int)$user['id'],
                    'regular', // Default user type
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

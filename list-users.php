<?php
// List all users and their types
require_once(__DIR__ . '/database/database.php');

$db = Database::getInstance();
$stmt = $db->query('SELECT id, name, username, email, user_type FROM User');
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($users)) {
    echo "No users found.";
} else {
    echo "<table border='1' cellpadding='5'><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>User Type</th></tr>";
    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($user['id']) . "</td>";
        echo "<td>" . htmlspecialchars($user['name']) . "</td>";
        echo "<td>" . htmlspecialchars($user['username']) . "</td>";
        echo "<td>" . htmlspecialchars($user['email']) . "</td>";
        echo "<td>" . htmlspecialchars($user['user_type']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>

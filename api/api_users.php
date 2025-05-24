<?php

declare(strict_types=1);

require_once(__DIR__ . '/../api/api_security.php'); // Apply API security headers and CORS
require_once(__DIR__ . '/../database/security/security.php'); // Load security helpers
require_once(__DIR__ . '/../database/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/classes/user.class.php');
require_once(__DIR__ . '/../database/database.php');

$db = Database::getInstance();

// Check if the 'search' parameter is provided
$search = $_GET['search'] ?? '';

// Fetch users based on the search parameter or fetch all users if empty
$users = empty($search) ? User::getAllUsers($db) : User::searchUsers($db, $search, 8);

echo json_encode(array_map(function ($user) {
    return [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'username' => $user->getUsername(),
        'profilePicture' => $user->getProfileImage()
    ];
}, $users));

<?php

declare(strict_types=1);

require_once(__DIR__ . '/../api/api_security.php'); 
require_once(__DIR__ . '/../database/security/security.php'); 
require_once(__DIR__ . '/../database/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/classes/user.class.php');
require_once(__DIR__ . '/../database/database.php');

$db = Database::getInstance();

$search = $_GET['search'] ?? '';

$users = empty($search) ? User::getAllUsers($db) : User::searchUsers($db, $search, 8);

echo json_encode(array_map(function ($user) {
    return [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'username' => $user->getUsername(),
        'profilePicture' => $user->getProfileImage()
    ];
}, $users));

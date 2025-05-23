<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');

// Get the current session
$session = Session::getInstance();
$user = $session->getUser();

// Check if user is logged in
if (!$user) {
    // User is not logged in, redirect to login page
    header('Location: ../../index.php');
    exit();
}

// Delete the user's account
$userId = $user['id'];
$success = User::deleteAccount((int)$userId);

if ($success) {
    // Log the user out by destroying the session
    $session->logout();

    header('Location: ../../pages/index.php');
} else {
    // If deletion failed, redirect back to profile with error
    header('Location: ../../pages/profile.php?username=' . urlencode($user['username']) . '&error=delete_failed');
}

exit();

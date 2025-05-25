<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');

$session = Session::getInstance();
$user = $session->getUser();

if (!$user) {
    header('Location: ../../index.php');
    exit();
}

$userId = $user['id'];
$success = User::deleteAccount((int)$userId);

if ($success) {
    $session->logout();

    header('Location: /../../pages/index.php?showGoFlow=true');
    exit();
    exit();
} else {
    header('Location: /../../pages/users/profile.php?username=' . urlencode($user['username']) . '&error=delete_failed');
}

exit();

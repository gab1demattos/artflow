<?php
declare(strict_types=1);
require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../database/user.class.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    header('Location: /index.php?error=missing_credentials');
    exit();
}

$user = User::get_user_by_username_password($username, $password);
if (!$user) {
    header('Location: /index.php?error=invalid_credentials');
    exit();
}

$session = Session::getInstance();
$session->login($user);

// Redirect on success
header('Location: /index.php?signin=success');
exit();
?>
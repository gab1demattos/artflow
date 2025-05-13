<?php
declare(strict_types=1);
require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../database/user.class.php');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header('Location: /index.php?error=missing_credentials');
    exit();
}

$user = User::get_user_by_email_password($email, $password);
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
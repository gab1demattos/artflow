<?php
declare(strict_types=1);
require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../database/user.class.php');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo 'Missing credentials';
    exit();
}

$user = User::get_user_by_username_password($username, $password);
if (!$user) {
    http_response_code(401);
    echo 'Invalid credentials';
    exit();
}

$session = Session::getInstance();
$session->login($user);
http_response_code(200);
echo json_encode(['username' => $user['username']]);
exit();
?>
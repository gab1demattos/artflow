<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/security/security_bootstrap.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
require_once(__DIR__ . '/../../database/security/security.php');

function redirect_home()
{
    header('Location: /');
    exit();
}

function handle_signup_error($msg)
{
    $_SESSION['signup_error'] = $msg;
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_home();
}

$token = $_POST['csrf_token'] ?? '';
if (!CSRF::validate($token, 'signup_csrf_token')) {
    handle_signup_error('Invalid security token. Please try again.');
}

$name = Security::sanitizeInput($_POST['name'] ?? '');
$username = Security::sanitizeInput($_POST['username'] ?? '');
$email = Security::sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? ''; 
$confirm_password = $_POST['confirm_password'] ?? ''; 

$session = Session::getInstance();

if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    handle_signup_error('All fields are required');
} else if ($password !== $confirm_password) {
    handle_signup_error('Password and confirmation do not match');
}

if (strlen($username) < 3) {
    handle_signup_error('Username must be at least 3 characters.');
}
if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    handle_signup_error('Username can only contain letters, numbers, or underscores (_).');
}

$db = Database::getInstance();
$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    handle_signup_error('An account with this email already exists.');
}

$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetchColumn() > 0) {
    handle_signup_error('An account with this username already exists.');
}

if (strlen($password) < 8) {
    handle_signup_error('Password must be at least 8 characters.');
}
if (!preg_match('/[0-9]/', $password)) {
    handle_signup_error('Password must contain at least one number.');
}
if (!preg_match('/[A-Z]/', $password)) {
    handle_signup_error('Password must contain at least one uppercase letter.');
}
if (!preg_match('/[a-z]/', $password)) {
    handle_signup_error('Password must contain at least one lowercase letter.');
}
if (!preg_match('/[.\?\$#@!&%]/', $password)) {
    handle_signup_error('Password must contain at least one special character (.?$#@!&%).');
}

$user = User::create($name, $username, $email, $password, 'regular');
if ($user) {
    $session->login([
        'id' => $user->id,
        'name' => $user->name,
        'username' => $user->username,
        'user_type' => $user->user_type,
        'email' => $user->email,
        'bio' => $user->bio,
        'profile_image' => $user->profile_image
    ]);

    $_SESSION['signup_success'] = true;

    header('Location: /../../pages/index.php?showGoFlow=true');
    exit();
    exit();
} else {
    handle_signup_error('Account creation failed. Please try again.');
}

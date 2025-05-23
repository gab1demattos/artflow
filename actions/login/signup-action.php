<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/database.php');

// Use absolute path for redirect to homepage
function redirect_home() {
    header('Location: /pages/index.php');
    exit();
}

// If AJAX, return JSON error, else show error in alert
function handle_signup_error($msg) {
    echo '<script>
        if (window.parent && window.parent.showModalError) {
            window.parent.showModalError("signup-modal-overlay", "' . addslashes($msg) . '");
            if (window.parent.showModal) window.parent.showModal("signUp");
        } else {
            alert("' . addslashes($msg) . '");
        }
        if (window.parent) window.parent.postMessage({modalError: true}, "*");
    </script>';
    exit();
}

// Get form data
$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$session = Session::getInstance();

// Basic validation
if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    handle_signup_error('All fields are required');
} else if ($password !== $confirm_password) {
    handle_signup_error('Password and confirmation do not match');
}

// Username validation: at least 3 chars, only letters, numbers, or _
if (strlen($username) < 3) {
    handle_signup_error('Username must be at least 3 characters.');
}
if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
    handle_signup_error('Username can only contain letters, numbers, or underscores (_).');
}

// Check for existing email
$db = Database::getInstance();
$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    handle_signup_error('An account with this email already exists.');
}

// Check for existing username
$stmt = $db->prepare('SELECT COUNT(*) FROM User WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetchColumn() > 0) {
    handle_signup_error('An account with this username already exists.');
}

// Password length and complexity check
if (strlen($password) < 8) {
    handle_signup_error('Password must be at least 8 characters.');
}
if (!preg_match('/[0-9]/', $password)) {
    handle_signup_error('Password must contain at least one number.');
}
if (!preg_match('/[.\?\$#@!&%]/', $password)) {
    handle_signup_error('Password must contain at least one special character (.?$#@!&%).');
}

$user = User::create($name, $username, $email, $password, 'regular');
if ($user) {
    $session->login([
        'id' => $user->id,
        'user_type' => $user->user_type,
        'name' => $user->name,
        'username' => $user->username,
        'email' => $user->email,
        'bio' => $user->bio,
        'profile_image' => $user->profile_image
    ]);
    echo '<script>
            if (window.parent && window.parent.showGoFlowModal) {
                window.parent.showGoFlowModal();
            } else {
                window.parent.location.href = "/pages/index.php";
            }
        </script>';
    exit();
} else {
    handle_signup_error('Signup failed');
}

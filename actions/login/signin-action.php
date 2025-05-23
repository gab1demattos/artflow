<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');

function redirect_home() {
    header('Location: /pages/index.php');
    exit();
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
$session = Session::getInstance();

// Check if credentials are provided
if (empty($email) || empty($password)) {
    if ($isAjax) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing credentials']);
    } else {
        $_SESSION['error'] = 'Please provide both email and password';
        redirect_home();
    }
    exit();
}

// Validate credentials
$user = User::get_user_by_email_password($email, $password);
if (!$user) {
    if ($isAjax) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials']);
    } else {
        $_SESSION['error'] = 'Invalid email or password';
        redirect_home();
    }
    exit();
}

// Successful login
$session->login($user);

if ($isAjax) {
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    redirect_home();
}
exit();
?>
<?php
declare(strict_types=1);
require_once(__DIR__ . '/../includes/session.php');
require_once(__DIR__ . '/../database/user.class.php');

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
        header('Location: ../index.php');
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
        header('Location: ../index.php');
    }
    exit();
}

// Successful login
$session->login($user);

if ($isAjax) {
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    header('Location: ../index.php');
}
exit();
?>
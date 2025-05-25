<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
require_once(__DIR__ . '/../../database/security/security.php');
require_once(__DIR__ . '/../../database/security/rate_limiter.php');

function redirect_home()
{
    header('Location: /../../pages/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_home();
}

$clientIP = $_SERVER['REMOTE_ADDR'];
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if (RateLimiter::isLimited($clientIP, 'login')) {
    $timeRemaining = RateLimiter::getTimeRemaining($clientIP, 'login');
    if ($isAjax) {
        http_response_code(429); 
        echo json_encode(['error' => "Too many login attempts. Please try again in {$timeRemaining} seconds."]);
    } else {
        $_SESSION['error'] = "Too many login attempts. Please try again in {$timeRemaining} seconds.";
        redirect_home();
    }
    exit();
}

$token = $_POST['csrf_token'] ?? '';
if (!CSRF::validate($token, 'signin_csrf_token')) {
    if ($isAjax) {
        http_response_code(403);
        $errorDetails = 'Invalid security token';
        if (empty($token)) {
            $errorDetails .= ' (token is empty)';
        }
        if (!isset($_SESSION['signin_csrf_token'])) {
            $errorDetails .= ' (no token in session)';
        }
        echo json_encode(['error' => $errorDetails]);
    } else {
        $_SESSION['error'] = 'Invalid security token. Please try again.';
        redirect_home();
    }
    exit();
}

$email = Security::sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? ''; 

$session = Session::getInstance();

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

RateLimiter::logAttempt($clientIP, 'login');

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

RateLimiter::resetLimit($clientIP, 'login');

$session->login($user);

if ($isAjax) {
    http_response_code(200);
    echo json_encode(['success' => true]);
} else {
    redirect_home();
}
exit();

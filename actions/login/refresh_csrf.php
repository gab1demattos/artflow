<?php
declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = CSRF::generate('signin_csrf_token');
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

echo json_encode(['token' => $token]);
exit();

<?php
// filepath: /home/francisca/uni/2ano/2S/LTW/ltw-project-ltw07g05/actions/login/refresh_csrf.php
declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/security/csrf.php');

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate a new CSRF token
$token = CSRF::generate('signin_csrf_token');

// Set the appropriate headers for AJAX requests (no need for CORS as we're on same origin)
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Return it as JSON
echo json_encode(['token' => $token]);
exit();

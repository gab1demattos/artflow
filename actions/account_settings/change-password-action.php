<?php
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
require_once(__DIR__ . '/../../database/security/security.php');

// Get the session and check if user is logged in
$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    header('Location: ../../index.php');
    exit;
}

// Verify that the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    $token = $_POST['csrf_token'] ?? '';
    if (!CSRF::validate($token, 'change_password_csrf_token')) {
        $_SESSION['error'] = 'Invalid security token. Please try again.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $userId = $currentUser['id'];
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];

    // Validate form data
    if (empty($oldPassword) || empty($newPassword)) {
        $_SESSION['error'] = "All fields are required";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Get database connection
    $db = Database::getInstance();

    // Verify old password
    $stmt = $db->prepare('SELECT password FROM User WHERE id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();

    // Check if password_verify works (new format)
    if ($result && password_verify($oldPassword, $result['password'])) {
        // Password matches using new format
    }
    // Backward compatibility with old sha1 hashing
    else if ($result && $result['password'] === sha1($oldPassword)) {
        // Password matches using old format
    } else {
        $_SESSION['error'] = "Incorrect password";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        // Use User class method to update with proper hashing
        $success = User::updatePassword($userId, $newPassword);

        if ($success) {
            $_SESSION['success'] = "Password changed successfully";
        } else {
            $_SESSION['error'] = "Failed to update password";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    // Redirect back to the previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

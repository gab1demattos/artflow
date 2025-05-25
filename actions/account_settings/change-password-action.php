<?php
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/classes/user.class.php');
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
require_once(__DIR__ . '/../../database/security/security.php');

$session = Session::getInstance();
$currentUser = $session->getUser();

if (!$currentUser) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!CSRF::validate($token, 'change_password_csrf_token')) {
        $_SESSION['error'] = 'Invalid security token. Please try again.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $userId = $currentUser['id'];
    $oldPassword = $_POST['old-password'];
    $newPassword = $_POST['new-password'];

    if (empty($oldPassword) || empty($newPassword)) {
        $_SESSION['error'] = "All fields are required";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $db = Database::getInstance();

    $stmt = $db->prepare('SELECT password FROM User WHERE id = ?');
    $stmt->execute([$userId]);
    $result = $stmt->fetch();

    if ($result && password_verify($oldPassword, $result['password'])) {
    }
    else if ($result && $result['password'] === sha1($oldPassword)) {
    } else {
        $_SESSION['error'] = "Incorrect password";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    try {
        $success = User::updatePassword($userId, $newPassword);

        if ($success) {
            $_SESSION['success'] = "Password changed successfully";
        } else {
            $_SESSION['error'] = "Failed to update password";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

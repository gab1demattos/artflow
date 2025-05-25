<?php

declare(strict_types=1);
require_once(__DIR__ . '/../../database/session.php');
require_once(__DIR__ . '/../../database/database.php');
require_once(__DIR__ . '/../../database/security/csrf.php');
require_once(__DIR__ . '/../../database/security/security.php');

$session = Session::getInstance();
$user = $session->getUser();

if (!$user) {
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/users/profile.php?username=' . $user['username']);
    exit();
}

$token = $_POST['csrf_token'] ?? '';
if (!CSRF::validate($token, 'edit_profile_csrf_token')) {
    $_SESSION['error'] = 'Invalid security token. Please try again.';
    header('Location: ../../pages/users/profile.php?username=' . $user['username']);
    exit();
}

$name = Security::sanitizeInput($_POST['name'] ?? '');
$username = Security::sanitizeInput($_POST['username'] ?? '');
$email = Security::sanitizeInput($_POST['email'] ?? '');
$bio = Security::sanitizeInput($_POST['bio'] ?? '');
$resetProfileImage = isset($_POST['reset_profile_image']) && $_POST['reset_profile_image'] === '1';

if (empty($name) || empty($username) || empty($email)) {
    $_SESSION['error'] = 'Name, username, and email are required';
    header('Location: ../../pages/users/profile.php?username=' . $user['username']);
    exit();
}

if ($username !== $user['username']) {
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT id FROM User WHERE username = ? AND id != ?');
    $stmt->execute([$username, $user['id']]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $_SESSION['error'] = 'Username is already taken';
        header('Location: ../../pages/users/profile.php?username=' . $user['username']);
        exit();
    }
}

if ($email !== $user['email']) {
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT id FROM User WHERE email = ? AND id != ?');
    $stmt->execute([$email, $user['id']]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        $_SESSION['error'] = 'Email is already taken';
        header('Location: ../../pages/users/profile.php?username=' . $user['username']);
        exit();
    }
}

$profileImage = $user['profile_image'] ?? null; 

if ($resetProfileImage) {
    $profileImage = '../../images/user_pfp/default.png';
}
else if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $imageValidation = Security::validateImageUpload(
        $_FILES['profile_image'],
        ['image/jpeg', 'image/png', 'image/gif', 'image/webp'],
        2097152 // 2MB limit
    );

    if (!$imageValidation['valid']) {
        $_SESSION['error'] = $imageValidation['error'];
        header('Location: ../../pages/users/profile.php?username=' . $user['username']);
        exit();
    }

    $uploadsDir = __DIR__ . '/../../images/user_pfp/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }

    $fileInfo = pathinfo($_FILES['profile_image']['name']);
    $extension = isset($fileInfo['extension']) ? '.' . $fileInfo['extension'] : '';
    $filename = uniqid('user_', true) . $extension;
    $targetPath = $uploadsDir . $filename;

    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
        $profileImage = '../../images/user_pfp/' . $filename;
    }
}

$db = Database::getInstance();
$stmt = $db->prepare('UPDATE User SET name = ?, username = ?, email = ?, bio = ?, profile_image = ? WHERE id = ?');
$success = $stmt->execute([$name, $username, $email, $bio, $profileImage, $user['id']]);

if ($success) {
    $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
    $stmt->execute([$user['id']]);
    $updatedUser = $stmt->fetch();

    if ($updatedUser) {
        $session->login($updatedUser);
        $_SESSION['success'] = 'Profile updated successfully';
    }

    header('Location: ../../pages/users/profile.php?username=' . $username);
    exit();
} else {
    $_SESSION['error'] = 'Failed to update profile';
    header('Location: ../../pages/users/profile.php?username=' . $user['username']);
    exit();
}

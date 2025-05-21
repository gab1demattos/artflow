<?php
declare(strict_types=1);
require_once(__DIR__ . '/../database/session.php');
require_once(__DIR__ . '/../database/database.php');

// Check if user is logged in
$session = Session::getInstance();
$user = $session->getUser();

if (!$user) {
    // Not logged in, redirect to home
    header('Location: /');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Not a POST request
    header('Location: /pages/profile.php?username=' . $user['username']);
    exit();
}

// Get form data
$name = $_POST['name'] ?? '';
$username = $_POST['username'] ?? '';
$bio = $_POST['bio'] ?? '';
$resetProfileImage = isset($_POST['reset_profile_image']) && $_POST['reset_profile_image'] === '1';

// Basic validation
if (empty($name) || empty($username)) {
    $_SESSION['error'] = 'Name and username are required';
    header('Location: /pages/profile.php?username=' . $user['username']);
    exit();
}

// Check if username is already taken (if it's different from current username)
if ($username !== $user['username']) {
    $db = Database::getInstance();
    $stmt = $db->prepare('SELECT id FROM User WHERE username = ? AND id != ?');
    $stmt->execute([$username, $user['id']]);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        $_SESSION['error'] = 'Username is already taken';
        header('Location: /pages/profile.php?username=' . $user['username']);
        exit();
    }
}

// Process profile image if uploaded
$profileImage = $user['profile_image'] ?? null; // Keep existing by default

// If reset flag is set, set profile image to default
if ($resetProfileImage) {
    $profileImage = '/images/user_pfp/default.png';
} 
// Otherwise, process uploaded image if any
else if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = $_FILES['profile_image']['type'];
    
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = 'Only JPG, PNG, GIF, and WEBP images are allowed';
        header('Location: /pages/profile.php?username=' . $user['username']);
        exit();
    }
    
    // Save the image
    $uploadsDir = __DIR__ . '/../images/user_pfp/';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }
    
    $filename = uniqid('user_', true) . '_' . basename($_FILES['profile_image']['name']);
    $targetPath = $uploadsDir . $filename;
    
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetPath)) {
        $profileImage = '/images/user_pfp/' . $filename;
    }
}

// Update user in database
$db = Database::getInstance();
$stmt = $db->prepare('UPDATE User SET name = ?, username = ?, bio = ?, profile_image = ? WHERE id = ?');
$success = $stmt->execute([$name, $username, $bio, $profileImage, $user['id']]);

if ($success) {
    // Get updated user data for session
    $stmt = $db->prepare('SELECT * FROM User WHERE id = ?');
    $stmt->execute([$user['id']]);
    $updatedUser = $stmt->fetch();
    
    if ($updatedUser) {
        // Update user in session
        $session->login($updatedUser);
        $_SESSION['success'] = 'Profile updated successfully';
    }
    
    // Redirect to the profile page with the new username
    header('Location: /pages/profile.php?username=' . $username);
    exit();
} else {
    $_SESSION['error'] = 'Failed to update profile';
    header('Location: /pages/profile.php?username=' . $user['username']);
    exit();
}
?>
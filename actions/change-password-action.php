<?php
require_once(__DIR__ . '/../database/database.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/session.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: ../index.php');
    exit;
}

// Verify that the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['id'];
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
    
    if (!$result || $result['password'] !== sha1($oldPassword)) {
        $_SESSION['error'] = "Incorrect password";
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    
    // Update password - apply sha1 directly here instead of relying on the method
    $hashedPassword = sha1($newPassword);
    
    try {
        $updateStmt = $db->prepare('UPDATE User SET password = ? WHERE id = ?');
        $success = $updateStmt->execute([$hashedPassword, $userId]);
        
        if ($success) {
            // Double-check that the update worked
            $checkStmt = $db->prepare('SELECT password FROM User WHERE id = ?');
            $checkStmt->execute([$userId]);
            $updated = $checkStmt->fetch();
            
            if ($updated && $updated['password'] === $hashedPassword) {
                $_SESSION['success'] = "Password changed successfully";
            } else {
                $_SESSION['error'] = "Password update verification failed";
            }
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

<?php
    declare(strict_types=1);
    
    // Include necessary files
    require_once(__DIR__ . '/../includes/session.php');
    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../includes/database.php');
    
    // Start session
    $session = Session::getInstance();
    
    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        // If there's no session, just redirect to the homepage
        // This allows showing the role selection even to users who just registered
        header('Location: ../index.php?error=not_logged_in');
        exit;
    }
    
    // Get user roles from POST request
    $isClient = isset($_POST['client']) ? 1 : 0;
    $isFreelancer = isset($_POST['freelancer']) ? 1 : 0;
    
    // Check if at least one role is selected
    if ($isClient == 0 && $isFreelancer == 0) {
        header('Location: ../index.php?error=no_role_selected');
        exit;
    }
    
    // Get current user
    $user = $session->getUser();
    $userId = $user['id'];
    
    try {
        // Update user roles in database
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE User SET isClient = ?, isFreelancer = ? WHERE id = ?');
        $stmt->execute([$isClient, $isFreelancer, $userId]);
        
        // Update session user data
        $user['isClient'] = $isClient;
        $user['isFreelancer'] = $isFreelancer;
        $_SESSION['user'] = $user;
        
        // Redirect to home page or dashboard
        header('Location: ../index.php?role_updated=success');
    } catch (PDOException $e) {
        // Handle database error
        header('Location: ../index.php?error=database_error');
    }
?>
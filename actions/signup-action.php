<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/user.class.php');

    // Get form data
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Basic validation
    if (empty($name) || empty($username) || empty($email) || empty($password)) {
        header('Location: /index.php?error=empty_fields');
        exit();
    }

    if ($password !== $confirm_password) {
        header('Location: /index.php?error=password_mismatch');
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /index.php?error=invalid_email');
        exit();
    }

    try {
        // Create user with both roles set to true (1)
        User::create(
            'regular',  // user_type
            1,          // isClient (true)
            1,          // isFreelancer (true)
            $name,
            $username,
            $email,
            $password
        );
        
        // Redirect on success
        header('Location: /index.php?signup=success&username=' . urlencode($username));
        exit();
    } catch (PDOException $e) {
        error_log("Signup error: " . $e->getMessage());
        header('Location: /index.php?error=signup_failed');
        exit();
    }
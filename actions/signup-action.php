<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/user.class.php');
    require_once(__DIR__ . '/../includes/session.php');

    // Get form data
    $name = $_POST['name'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $session = Session::getInstance();
    
    // Basic validation
    if (empty($name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION['error'] = 'All fields are required';
        header('Location: ../index.php');
        exit();
    } else if ($password !== $confirm_password) {
        $_SESSION['error'] = 'Password and confirmation do not match';
        header('Location: ../index.php');
        exit();
    } else {
        $user = User::create('regular', $name, $username, $email, $password);
        if ($user) {
            // Set client-side storage variables via JavaScript
            echo '<script>
                sessionStorage.setItem("signup_success", "true");
                sessionStorage.setItem("signup_username", "' . addslashes($email) . '");
                sessionStorage.setItem("signup_password", "' . addslashes($password) . '");
                window.location.href = "../index.php";
            </script>';
            exit();
        } else {
            $_SESSION['error'] = 'Signup failed';
            header('Location: ../index.php');
            exit();
        }
    }
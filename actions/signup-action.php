<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../database/user.class.php');

    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header('Location: /signup?error=password_mismatch');
        exit();
    }

    User::create($name, $username, $email, $password);

    header('Location: /');
?>
<?php

declare(strict_types=1);


class CSRF
{
    public static function generate(string $key = 'csrf_token'): string
    {
        $token = bin2hex(random_bytes(32));

        $_SESSION[$key] = $token;
        return $token;
    }

    
    public static function validate(string $token, string $key = 'csrf_token'): bool
    {
        if (!isset($_SESSION[$key]) || $_SESSION[$key] !== $token) {
            return false;
        }

        unset($_SESSION[$key]);
        return true;
    }

    public static function getTokenField(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

   
    public static function getJavaScriptToken(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return "<script>var csrfToken = '$token';</script>";
    }

   
    public static function getMetaToken(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}

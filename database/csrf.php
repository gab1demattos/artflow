<?php

declare(strict_types=1);

/**
 * CSRF Protection Class
 * Handles generation, validation and storage of CSRF tokens
 */
class CSRF
{
    /**
     * Generate a new CSRF token and store it in the session
     * @param string $key The key to store the token under
     * @return string The generated token
     */
    public static function generate(string $key = 'csrf_token'): string
    {
        // Generate a cryptographically secure random token
        $token = bin2hex(random_bytes(32));

        // Store in session
        $_SESSION[$key] = $token;
        return $token;
    }

    /**
     * Validate a submitted CSRF token against the stored one
     * @param string $token The token to validate
     * @param string $key The key the token is stored under
     * @return bool True if the token is valid, false otherwise
     */
    public static function validate(string $token, string $key = 'csrf_token'): bool
    {
        // If no token in session or token doesn't match
        if (!isset($_SESSION[$key]) || $_SESSION[$key] !== $token) {
            return false;
        }

        // Remove the used token for one-time use
        unset($_SESSION[$key]);
        return true;
    }

    /**
     * Get HTML for a hidden CSRF token input field
     * @param string $key The key to store/retrieve the token under
     * @return string HTML input field with CSRF token
     */
    public static function getTokenField(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Get a CSRF token that can be accessed via JavaScript for AJAX requests
     * @param string $key The key to store/retrieve the token under
     * @return string JavaScript code to set a global CSRF token
     */
    public static function getJavaScriptToken(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return "<script>var csrfToken = '$token';</script>";
    }

    /**
     * Get meta tag with CSRF token for AJAX requests
     * @param string $key The key to store/retrieve the token under
     * @return string HTML meta tag with CSRF token
     */
    public static function getMetaToken(string $key = 'csrf_token'): string
    {
        $token = self::generate($key);
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}

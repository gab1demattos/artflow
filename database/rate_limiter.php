<?php

declare(strict_types=1);

/**
 * Rate Limiter Class
 * Handles rate limiting for login attempts and other sensitive operations
 */
class RateLimiter
{
    private const MAX_ATTEMPTS = 5; // Maximum number of attempts allowed
    private const TIME_WINDOW = 300; // Time window in seconds (5 minutes)

    /**
     * Check if an IP address has exceeded the rate limit
     * 
     * @param string $ip The IP address to check
     * @param string $action The action being rate limited (e.g., 'login', 'password_reset')
     * @return bool True if rate limit exceeded, false otherwise
     */
    public static function isLimited(string $ip, string $action = 'login'): bool
    {
        // Get the current attempts for this IP and action
        $attempts = self::getAttempts($ip, $action);

        // If attempts exceed the max allowed within the time window, limit access
        if ($attempts >= self::MAX_ATTEMPTS) {
            return true;
        }

        return false;
    }

    /**
     * Log an attempt for an IP address
     * 
     * @param string $ip The IP address to log
     * @param string $action The action being performed
     * @return void
     */
    public static function logAttempt(string $ip, string $action = 'login'): void
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Initialize the attempts array if it doesn't exist
        if (!isset($_SESSION['rate_limit_attempts'])) {
            $_SESSION['rate_limit_attempts'] = [];
        }

        $key = $ip . '_' . $action;

        // Initialize the attempt counter for this IP and action if it doesn't exist
        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            $_SESSION['rate_limit_attempts'][$key] = [
                'count' => 0,
                'first_attempt' => time(),
                'last_attempt' => time()
            ];
        }

        // Update the attempt information
        $_SESSION['rate_limit_attempts'][$key]['count']++;
        $_SESSION['rate_limit_attempts'][$key]['last_attempt'] = time();
    }

    /**
     * Get the number of attempts for an IP address within the time window
     * 
     * @param string $ip The IP address to check
     * @param string $action The action being checked
     * @return int Number of attempts
     */
    private static function getAttempts(string $ip, string $action = 'login'): int
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If no attempts recorded yet, return 0
        if (!isset($_SESSION['rate_limit_attempts'])) {
            return 0;
        }

        $key = $ip . '_' . $action;

        // If no attempts for this IP and action, return 0
        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            return 0;
        }

        $attempts = $_SESSION['rate_limit_attempts'][$key];
        $now = time();

        // If the first attempt is outside the time window, reset the counter
        if ($now - $attempts['first_attempt'] > self::TIME_WINDOW) {
            $_SESSION['rate_limit_attempts'][$key] = [
                'count' => 0,
                'first_attempt' => $now,
                'last_attempt' => $now
            ];
            return 0;
        }

        return $attempts['count'];
    }

    /**
     * Get remaining time (in seconds) until the rate limit is reset
     * 
     * @param string $ip The IP address to check
     * @param string $action The action being checked
     * @return int Seconds remaining until reset
     */
    public static function getTimeRemaining(string $ip, string $action = 'login'): int
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If no attempts recorded yet, return 0
        if (!isset($_SESSION['rate_limit_attempts'])) {
            return 0;
        }

        $key = $ip . '_' . $action;

        // If no attempts for this IP and action, return 0
        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            return 0;
        }

        $attempts = $_SESSION['rate_limit_attempts'][$key];
        $now = time();

        // Calculate time remaining until reset
        $timeElapsed = $now - $attempts['first_attempt'];
        $timeRemaining = self::TIME_WINDOW - $timeElapsed;

        return max(0, $timeRemaining);
    }

    /**
     * Reset the rate limit for an IP address
     * 
     * @param string $ip The IP address to reset
     * @param string $action The action to reset
     * @return void
     */
    public static function resetLimit(string $ip, string $action = 'login'): void
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Return if there's no rate limiting data
        if (!isset($_SESSION['rate_limit_attempts'])) {
            return;
        }

        $key = $ip . '_' . $action;

        // Remove the rate limit for this IP and action
        if (isset($_SESSION['rate_limit_attempts'][$key])) {
            unset($_SESSION['rate_limit_attempts'][$key]);
        }
    }
}

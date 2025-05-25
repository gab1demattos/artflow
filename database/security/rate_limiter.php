<?php

declare(strict_types=1);

class RateLimiter
{
    private const MAX_ATTEMPTS = 5;
    private const TIME_WINDOW = 300;


    public static function isLimited(string $ip, string $action = 'login'): bool
    {
        $attempts = self::getAttempts($ip, $action);

        if ($attempts >= self::MAX_ATTEMPTS) {
            return true;
        }

        return false;
    }

   
    public static function logAttempt(string $ip, string $action = 'login'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['rate_limit_attempts'])) {
            $_SESSION['rate_limit_attempts'] = [];
        }

        $key = $ip . '_' . $action;

        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            $_SESSION['rate_limit_attempts'][$key] = [
                'count' => 0,
                'first_attempt' => time(),
                'last_attempt' => time()
            ];
        }

        $_SESSION['rate_limit_attempts'][$key]['count']++;
        $_SESSION['rate_limit_attempts'][$key]['last_attempt'] = time();
    }

    private static function getAttempts(string $ip, string $action = 'login'): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['rate_limit_attempts'])) {
            return 0;
        }

        $key = $ip . '_' . $action;

        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            return 0;
        }

        $attempts = $_SESSION['rate_limit_attempts'][$key];
        $now = time();

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

   
    public static function getTimeRemaining(string $ip, string $action = 'login'): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['rate_limit_attempts'])) {
            return 0;
        }

        $key = $ip . '_' . $action;

        if (!isset($_SESSION['rate_limit_attempts'][$key])) {
            return 0;
        }

        $attempts = $_SESSION['rate_limit_attempts'][$key];
        $now = time();

        $timeElapsed = $now - $attempts['first_attempt'];
        $timeRemaining = self::TIME_WINDOW - $timeElapsed;

        return max(0, $timeRemaining);
    }

    public static function resetLimit(string $ip, string $action = 'login'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['rate_limit_attempts'])) {
            return;
        }

        $key = $ip . '_' . $action;

        if (isset($_SESSION['rate_limit_attempts'][$key])) {
            unset($_SESSION['rate_limit_attempts'][$key]);
        }
    }
}

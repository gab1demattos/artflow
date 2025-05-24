<?php

/**
 * API Security Helper
 * Apply security headers and CORS settings for API endpoints
 */

// Set CORS headers to restrict access to your domain
function setCorsHeaders()
{
    // Allow requests only from your own domain in production
    // For development, you might want to be less restrictive
    header("Access-Control-Allow-Origin: *"); // Restrict this in production

    // Allow specific HTTP methods
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    // Allow specific headers in requests
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    // Cache preflight response for 1 hour (3600 seconds)
    header("Access-Control-Max-Age: 3600");

    // Allow cookies and authentication to be sent
    header("Access-Control-Allow-Credentials: true");

    // Handle preflight OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        // Preflight request - respond with 200 OK
        http_response_code(200);
        exit;
    }
}

// Set security headers for API endpoints
function setApiSecurityHeaders()
{
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');

    // Prevent clickjacking
    header('X-Frame-Options: DENY');

    // Help prevent XSS attacks
    header('X-XSS-Protection: 1; mode=block');

    // Set strict referrer policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Set JSON content type
function setJsonContentType()
{
    header('Content-Type: application/json; charset=UTF-8');
}

// Apply all API security headers
function applyApiSecurity()
{
    setCorsHeaders();
    setApiSecurityHeaders();
    setJsonContentType();
}

// Call this at the beginning of API files
applyApiSecurity();

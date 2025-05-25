<?php

function setCorsHeaders()
{
    header("Access-Control-Allow-Origin: *"); 

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

function setApiSecurityHeaders()
{
// Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');

    // Help prevent XSS attacks
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

function setJsonContentType()
{
    header('Content-Type: application/json; charset=UTF-8');
}

function applyApiSecurity()
{
    setCorsHeaders();
    setApiSecurityHeaders();
    setJsonContentType();
}

applyApiSecurity();

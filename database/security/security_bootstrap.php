<?php

/**
 * Security Bootstrap File
 * Include this file at the beginning of each PHP file to apply security headers
 */
require_once(__DIR__ . '/csp_helper.php');

// Apply Content Security Policy
CSPHelper::apply();

// Add additional security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()');

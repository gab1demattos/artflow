<?php

declare(strict_types=1);

/**
 * Security Helper Class
 * Contains methods for sanitizing input/output and other security-related functions
 */
class Security
{
    /**
     * Sanitize output to prevent XSS
     * @param mixed $data Data to be sanitized
     * @return mixed Sanitized data
     */
    public static function sanitizeOutput($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::sanitizeOutput($value);
            }
            return $data;
        }
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize input data
     * @param mixed $data Data to be sanitized
     * @return mixed Sanitized data
     */
    public static function sanitizeInput($data)
    {
        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                // Sanitize the key as well
                $cleanKey = self::sanitizeInput($key);
                $sanitized[$cleanKey] = self::sanitizeInput($value);
            }
            return $sanitized;
        }

        if (is_string($data)) {
            // Remove potentially dangerous characters
            $data = trim($data);
            // Filter out any script tags and dangerous attributes
            $data = filter_var($data, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            return $data;
        }

        return $data;
    }

    /**
     * Escape data for safe use in JavaScript
     * Use this when outputting data directly into JavaScript code
     * 
     * @param mixed $data Data to be escaped for JavaScript
     * @return string JavaScript-safe string
     */
    public static function escapeForJS($data): string
    {
        if (is_array($data)) {
            return json_encode(self::sanitizeOutput($data)) ?: '{}';
        }

        if (is_null($data)) {
            return 'null';
        }

        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }

        if (is_numeric($data)) {
            return (string)$data;
        }

        // For strings, escape all potentially dangerous characters
        $output = (string)$data;
        $output = str_replace('\\', '\\\\', $output); // Escape backslashes
        $output = str_replace('"', '\\"', $output);  // Escape double quotes
        $output = str_replace("'", "\\'", $output);  // Escape single quotes
        $output = str_replace("\r", '\\r', $output); // Escape carriage returns
        $output = str_replace("\n", '\\n', $output); // Escape newlines
        $output = str_replace("\t", '\\t', $output); // Escape tabs
        $output = str_replace('<', '\\x3C', $output); // Escape < to prevent </script>
        $output = str_replace('>', '\\x3E', $output); // Escape > to prevent </script>
        $output = str_replace('&', '\\x26', $output); // Escape & to prevent entities

        return $output;
    }

    /**
     * Safely prepare an array for JavaScript use
     * Useful for passing PHP arrays to JavaScript
     * 
     * @param array $data Array to be prepared for JavaScript
     * @return string JavaScript-safe JSON string
     */
    public static function jsonEncodeForHTML(array $data): string
    {
        return htmlspecialchars(json_encode($data) ?: '{}', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Validate if input is a valid email
     * @param string $email Email to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate if input is a valid integer
     * @param mixed $input Input to validate
     * @return bool True if valid integer, false otherwise
     */
    public static function validateInteger($input): bool
    {
        return filter_var($input, FILTER_VALIDATE_INT) !== false;
    }

    /**
     * Validate if input is a valid float
     * @param mixed $input Input to validate  
     * @return bool True if valid float, false otherwise
     */
    public static function validateFloat($input): bool
    {
        return filter_var($input, FILTER_VALIDATE_FLOAT) !== false;
    }

    /**
     * Validate if input is a valid URL
     * @param string $url URL to validate
     * @return bool True if valid, false otherwise
     */
    public static function validateUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate uploaded file is an image with allowed extensions
     * 
     * @param array $file The $_FILES array element for the uploaded file
     * @param array $allowedTypes Array of allowed mime types
     * @param int $maxSize Maximum file size in bytes (default 2MB)
     * @return array Array with 'valid' boolean and 'error' message if invalid
     */
    public static function validateImageUpload(array $file, array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'], int $maxSize = 2097152): array
    {
        // Check if file exists and has no errors
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return [
                'valid' => false,
                'error' => 'File upload error: ' . self::getUploadErrorMessage($file['error'] ?? UPLOAD_ERR_NO_FILE)
            ];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return [
                'valid' => false,
                'error' => 'File too large. Maximum size is ' . self::formatBytes($maxSize)
            ];
        }

        // Check file type by MIME
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, $allowedTypes)) {
            return [
                'valid' => false,
                'error' => 'Invalid file type. Allowed: ' . implode(', ', array_map(function ($type) {
                    return str_replace('image/', '', $type);
                }, $allowedTypes))
            ];
        }

        // Validate image dimensions and integrity
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return [
                'valid' => false,
                'error' => 'Invalid image file'
            ];
        }

        // If we've made it this far, validation is successful
        return [
            'valid' => true,
            'error' => null,
            'mime_type' => $mimeType,
            'dimensions' => [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1]
            ]
        ];
    }

    /**
     * Get user-friendly upload error message
     * 
     * @param int $errorCode PHP upload error code
     * @return string Human-readable error message
     */
    private static function getUploadErrorMessage(int $errorCode): string
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload stopped by extension';
            default:
                return 'Unknown upload error';
        }
    }

    /**
     * Format bytes to human-readable string
     * 
     * @param int $bytes Number of bytes
     * @param int $precision Precision of decimal places
     * @return string Formatted size string
     */
    private static function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

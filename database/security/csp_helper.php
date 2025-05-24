<?php

declare(strict_types=1);

/**
 * Content Security Policy Helper Class
 * Provides methods to define and apply CSP headers
 */
class CSPHelper
{
    // Default CSP directives
    private static $directives = [
        'default-src' => ['\'self\''],
        'script-src' => ['\'self\'', 'cdn.jsdelivr.net', '\'unsafe-inline\''],
        'style-src' => ['\'self\'', 'fonts.googleapis.com', '\'unsafe-inline\''],
        'img-src' => ['\'self\'', 'data:'],
        'font-src' => ['\'self\'', 'fonts.gstatic.com'],
        'connect-src' => ['\'self\''],
        'frame-src' => ['\'self\''],
        'frame-ancestors' => ['\'self\''],
        'form-action' => ['\'self\''],
    ];

    /**
     * Add a source to a specific directive
     *
     * @param string $directive The CSP directive to modify
     * @param string|array $source The source(s) to add
     * @return void
     */
    public static function addSource(string $directive, $source): void
    {
        if (!isset(self::$directives[$directive])) {
            self::$directives[$directive] = [];
        }

        if (is_array($source)) {
            self::$directives[$directive] = array_merge(self::$directives[$directive], $source);
        } else {
            self::$directives[$directive][] = $source;
        }

        // Remove duplicates
        self::$directives[$directive] = array_unique(self::$directives[$directive]);
    }

    /**
     * Apply the Content Security Policy as an HTTP header
     * Call this method early in the request lifecycle, before any output
     * 
     * @param bool $reportOnly Set to true to use report-only mode (no blocking)
     * @return void
     */
    public static function apply(bool $reportOnly = false): void
    {
        $headerName = $reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $headerValue = self::buildHeaderValue();
        header("$headerName: $headerValue");
    }

    /**
     * Build the CSP header value from directives
     *
     * @return string The complete CSP header value
     */
    private static function buildHeaderValue(): string
    {
        $parts = [];

        foreach (self::$directives as $directive => $sources) {
            if (count($sources) > 0) {
                $parts[] = $directive . ' ' . implode(' ', $sources);
            }
        }

        return implode('; ', $parts);
    }

    /**
     * Enable strict CSP (removes unsafe-inline and unsafe-eval)
     * Warning: This might break functionality if your site relies on inline scripts
     *
     * @return void
     */
    public static function enableStrict(): void
    {
        // Remove unsafe directives
        if (isset(self::$directives['script-src'])) {
            self::$directives['script-src'] = array_diff(self::$directives['script-src'], ['\'unsafe-inline\'', '\'unsafe-eval\'']);
        }

        if (isset(self::$directives['style-src'])) {
            self::$directives['style-src'] = array_diff(self::$directives['style-src'], ['\'unsafe-inline\'']);
        }

        // Add nonces or hashes instead if needed
        // This would require a more complex implementation
    }

    /**
     * Configure CSP for development mode (less strict)
     * 
     * @return void
     */
    public static function configureDevelopmentMode(): void
    {
        self::addSource('script-src', ['\'unsafe-eval\'', 'http://localhost:*']);
        self::addSource('connect-src', ['http://localhost:*', 'ws://localhost:*']);
    }

    /**
     * Reset the CSP directives to default values
     * 
     * @return void
     */
    public static function reset(): void
    {
        self::$directives = [
            'default-src' => ['\'self\''],
            'script-src' => ['\'self\'', 'cdn.jsdelivr.net', '\'unsafe-inline\''],
            'style-src' => ['\'self\'', 'fonts.googleapis.com', '\'unsafe-inline\''],
            'img-src' => ['\'self\'', 'data:'],
            'font-src' => ['\'self\'', 'fonts.gstatic.com'],
            'connect-src' => ['\'self\''],
            'frame-src' => ['\'self\''],
            'frame-ancestors' => ['\'self\''],
            'form-action' => ['\'self\''],
        ];
    }
}

<?php

declare(strict_types=1);

class CSPHelper
{
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

        self::$directives[$directive] = array_unique(self::$directives[$directive]);
    }

    public static function apply(bool $reportOnly = false): void
    {
        $headerName = $reportOnly
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $headerValue = self::buildHeaderValue();
        header("$headerName: $headerValue");
    }

    
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

    public static function enableStrict(): void
    {
        if (isset(self::$directives['script-src'])) {
            self::$directives['script-src'] = array_diff(self::$directives['script-src'], ['\'unsafe-inline\'', '\'unsafe-eval\'']);
        }

        if (isset(self::$directives['style-src'])) {
            self::$directives['style-src'] = array_diff(self::$directives['style-src'], ['\'unsafe-inline\'']);
        }

    }

    public static function configureDevelopmentMode(): void
    {
        self::addSource('script-src', ['\'unsafe-eval\'', 'http://localhost:*']);
        self::addSource('connect-src', ['http://localhost:*', 'ws://localhost:*']);
    }

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

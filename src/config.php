<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'hardzone';
const DB_USER = 'root';
const DB_PASS = '';

const SITE_NAME = 'HardZone';
const BASE_URL = '';

function appBaseUrl(): string
{
    if (BASE_URL !== '') {
        return '/' . trim(BASE_URL, '/');
    }

    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    $publicPos = strpos($scriptName, '/public/');
    if ($publicPos !== false) {
        return rtrim(substr($scriptName, 0, $publicPos + 7), '/');
    }

    if (str_ends_with($scriptName, '/public')) {
        return rtrim($scriptName, '/');
    }

    return '';
}

function url(string $path = ''): string
{
    $normalizedPath = '/' . ltrim($path, '/');

    return appBaseUrl() . $normalizedPath;
}


function assetUrl(string $path): string
{
    $normalizedPath = '/' . ltrim($path, '/');
    $fullPath = realpath(__DIR__ . '/../public' . $normalizedPath);

    if ($fullPath === false || !is_file($fullPath)) {
        return url($normalizedPath);
    }

    $mtime = filemtime($fullPath);
    if ($mtime === false) {
        return url($normalizedPath);
    }

    return url($normalizedPath) . '?v=' . $mtime;
}

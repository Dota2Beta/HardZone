<?php

declare(strict_types=1);

const DB_HOST = '127.0.0.1';
const DB_NAME = 'hardzone';
const DB_USER = 'root';
const DB_PASS = '';

const SITE_NAME = 'HardZone';
const BASE_URL = '';

function url(string $path = ''): string
{
    $base = rtrim(BASE_URL, '/');
    $normalizedPath = '/' . ltrim($path, '/');

    return $base . $normalizedPath;
}

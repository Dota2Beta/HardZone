<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/config.php';

$defaultTarget = url('/index.php');
$requestedTarget = (string) ($_GET['to'] ?? $defaultTarget);

$host = parse_url($requestedTarget, PHP_URL_HOST);
if ($host !== null) {
    $requestedTarget = $defaultTarget;
}

if (!str_starts_with($requestedTarget, '/')) {
    $requestedTarget = '/' . ltrim($requestedTarget, '/');
}

header('Location: ' . $requestedTarget, true, 302);
exit;

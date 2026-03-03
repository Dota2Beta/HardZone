<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/cart.php';
$user = currentUser();
$flashes = getFlashes();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= assetUrl('/assets/css/style.css') ?>">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark hz-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('/index.php') ?>">⚡ HardZone</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 w-100 justify-content-lg-end">
                <li class="nav-item"><a class="nav-link" href="<?= url('/index.php') ?>">Главная</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/catalog.php') ?>">Каталог</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/about.php') ?>">О нас</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/reviews.php') ?>">Отзывы</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/feedback.php') ?>">Обратная связь</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= url('/cart.php') ?>">Корзина (<?= cartCount() ?>)</a></li>
                <?php if ($user): ?>
                    <?php if ($user['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= url('/admin/index.php') ?>">Админ-панель</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="btn btn-sm btn-outline-light px-3" href="<?= url('/logout.php') ?>">Выход</a></li>
                    <?php
                        $navAvatar = $user['avatar_path'] ?? null;
                        $username = (string) ($user['username'] ?? '');
                        $initials = mb_strtoupper(mb_substr(trim($username), 0, 2));
                        if ($initials === '') {
                            $initials = 'U';
                        }
                    ?>
                    <li class="nav-item ms-lg-3 avatar-nav-item">
                        <a class="nav-avatar-link" href="<?= url('/profile.php') ?>" title="Профиль" aria-label="Профиль">
                            <?php if ($navAvatar): ?>
                                <img src="<?= htmlspecialchars($navAvatar) ?>" class="nav-avatar nav-avatar-lg" alt="avatar">
                            <?php else: ?>
                                <span class="nav-avatar nav-avatar-lg nav-avatar-placeholder"><?= htmlspecialchars($initials) ?></span>
                            <?php endif; ?>
                            <span class="nav-username"><?= htmlspecialchars($username) ?></span>
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= url('/login.php') ?>">Вход</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-warning text-dark px-3" href="<?= url('/register.php') ?>">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
    <?php foreach ($flashes as $flash): ?>
        <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
    <?php endforeach; ?>

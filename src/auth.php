<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function currentUser(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $fields = 'id, username, email, phone, role, created_at';
    if (tableHasColumn('users', 'avatar_path')) {
        $fields .= ', avatar_path';
    }

    $stmt = db()->prepare('SELECT ' . $fields . ' FROM users WHERE id = :id');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        return null;
    }

    $user['avatar_path'] = $user['avatar_path'] ?? null;

    return $user;
}

function isLoggedIn(): bool
{
    return currentUser() !== null;
}

function isAdmin(): bool
{
    $user = currentUser();

    return $user !== null && $user['role'] === 'admin';
}

function requireAuth(): void
{
    if (!isLoggedIn()) {
        header('Location: ' . url('/login.php'));
        exit;
    }
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        header('Location: ' . url('/index.php'));
        exit;
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function getFlashes(): array
{
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return $items;
}

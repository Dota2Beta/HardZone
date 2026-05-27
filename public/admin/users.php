<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/config.php';
require_once __DIR__ . '/../../src/auth.php';

requireAdmin();
$current = currentUser();
$currentUserId = (int) ($current['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = (int) ($_POST['user_id'] ?? 0);

    if ($userId > 0) {
        if ($userId === $currentUserId && in_array($action, ['ban', 'unban', 'role'], true)) {
            flash('danger', 'Нельзя менять собственную роль или блокировать себя.');
            header('Location: ' . url('/admin/users.php'));
            exit;
        }

        if ($action === 'ban') {
            db()->prepare('UPDATE users SET is_banned = 1 WHERE id = :id')->execute(['id' => $userId]);
            flash('success', 'Пользователь заблокирован.');
        } elseif ($action === 'unban') {
            db()->prepare('UPDATE users SET is_banned = 0 WHERE id = :id')->execute(['id' => $userId]);
            flash('success', 'Пользователь разблокирован.');
        } elseif ($action === 'role') {
            $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
            db()->prepare('UPDATE users SET role = :role WHERE id = :id')->execute(['id' => $userId, 'role' => $role]);
            flash('success', 'Роль пользователя обновлена.');
        }
    }

    header('Location: ' . url('/admin/users.php'));
    exit;
}

$users = db()->query('SELECT id, username, email, phone, role, COALESCE(is_banned, 0) AS is_banned, created_at FROM users ORDER BY id DESC')->fetchAll();

require_once __DIR__ . '/../../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Пользователи</h2>
    <a href="<?= url('/admin/index.php') ?>" class="btn btn-outline-light">Назад в админку</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr><th>ID</th><th>Логин</th><th>Email</th><th>Телефон</th><th>Роль</th><th>Статус</th><th>Действия</th></tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= (int) $u['id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td>
                    <?php if ((int) $u['is_banned'] === 1): ?>
                        <span class="badge text-bg-danger">Забанен</span>
                    <?php else: ?>
                        <span class="badge text-bg-success">Активен</span>
                    <?php endif; ?>
                </td>
                <td class="d-flex gap-1 flex-wrap">
                    <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="role">
                        <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                        <input type="hidden" name="role" value="<?= $u['role'] === 'admin' ? 'user' : 'admin' ?>">
                        <button class="btn btn-sm btn-outline-warning" type="submit" <?= (int) $u['id'] === $currentUserId ? 'disabled' : '' ?>>
                            Сделать <?= $u['role'] === 'admin' ? 'user' : 'admin' ?>
                        </button>
                    </form>

                    <?php if ((int) $u['is_banned'] === 1): ?>
                        <form method="post" class="d-inline">
                            <input type="hidden" name="action" value="unban">
                            <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                            <button class="btn btn-sm btn-outline-success" type="submit" <?= (int) $u['id'] === $currentUserId ? 'disabled' : '' ?>>Разбанить</button>
                        </form>
                    <?php else: ?>
                        <form method="post" class="d-inline" onsubmit="return confirm('Заблокировать пользователя?')">
                            <input type="hidden" name="action" value="ban">
                            <input type="hidden" name="user_id" value="<?= (int) $u['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger" type="submit" <?= (int) $u['id'] === $currentUserId ? 'disabled' : '' ?>>Забанить</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

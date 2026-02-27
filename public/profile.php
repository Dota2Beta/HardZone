<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/upload.php';

requireAuth();
$user = currentUser();

if (empty($_SESSION['profile_captcha'])) {
    $_SESSION['profile_captcha'] = random_int(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';
    $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';
    $captcha = trim($_POST['captcha'] ?? '');

    try {
        if ($username === '' || $email === '' || $phone === '' || $captcha === '') {
            throw new RuntimeException('Заполните обязательные поля профиля.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Некорректный email.');
        }

        if (!preg_match('/^\+?[0-9\-\s]{10,20}$/', $phone)) {
            throw new RuntimeException('Некорректный номер телефона.');
        }

        if ((int) $captcha !== (int) ($_SESSION['profile_captcha'] ?? 0)) {
            throw new RuntimeException('Неверная капча.');
        }

        $existsStmt = db()->prepare('SELECT id FROM users WHERE (email = :email OR username = :username) AND id != :id');
        $existsStmt->execute([
            'email' => $email,
            'username' => $username,
            'id' => $user['id'],
        ]);

        if ($existsStmt->fetch()) {
            throw new RuntimeException('Пользователь с таким email или логином уже существует.');
        }

        $avatarPath = $user['avatar_path'];
        $uploaded = handleImageUpload('avatar', 'uploads/avatars');
        if ($uploaded !== null) {
            $avatarPath = $uploaded;
        }

        $params = [
            'id' => $user['id'],
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
            'avatar_path' => $avatarPath,
        ];

        $sql = 'UPDATE users SET username = :username, email = :email, phone = :phone, avatar_path = :avatar_path';

        if ($newPassword !== '' || $newPasswordConfirm !== '') {
            if (strlen($newPassword) < 6) {
                throw new RuntimeException('Новый пароль должен быть не короче 6 символов.');
            }

            if ($newPassword !== $newPasswordConfirm) {
                throw new RuntimeException('Новые пароли не совпадают.');
            }

            $sql .= ', password_hash = :password_hash';
            $params['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = :id';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        flash('success', 'Профиль успешно обновлён.');
        $_SESSION['profile_captcha'] = random_int(1000, 9999);
        header('Location: ' . url('/profile.php'));
        exit;
    } catch (RuntimeException $e) {
        flash('danger', $e->getMessage());
        $_SESSION['profile_captcha'] = random_int(1000, 9999);
    }
}

$user = currentUser();
$avatar = $user['avatar_path'] ?: 'https://ui-avatars.com/api/?background=253a8a&color=fff&name=' . urlencode($user['username']);

require_once __DIR__ . '/../templates/header.php';
?>
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card soft-card shadow-sm h-100">
            <div class="card-body text-center p-4">
                <img src="<?= htmlspecialchars($avatar) ?>" class="profile-avatar mb-3" alt="avatar">
                <h4 class="mb-1"><?= htmlspecialchars($user['username']) ?></h4>
                <p class="text-secondary mb-3">Роль: <?= htmlspecialchars($user['role']) ?></p>
                <div class="small text-secondary">Регистрация: <?= htmlspecialchars(date('d.m.Y', strtotime($user['created_at']))) ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card soft-card shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-3">Настройки профиля</h2>
                <p class="text-secondary">Здесь можно изменить аватар, логин, email, телефон и пароль (опционально).</p>
                <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Логин</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Телефон</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Аватар (JPG/PNG/WEBP)</label>
                            <input type="file" name="avatar" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Новый пароль (опционально)</label>
                            <input type="password" name="new_password" class="form-control" minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Повторите новый пароль</label>
                            <input type="password" name="new_password_confirm" class="form-control" minlength="6">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Капча: введите число <strong><?= (int) $_SESSION['profile_captcha'] ?></strong></label>
                            <input type="text" name="captcha" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning text-dark mt-4">Сохранить изменения</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

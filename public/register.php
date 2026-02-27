<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

if (isLoggedIn()) {
    header('Location: ' . url('/index.php'));
    exit;
}

if (empty($_SESSION['captcha'])) {
    $_SESSION['captcha'] = random_int(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $captcha = trim($_POST['captcha'] ?? '');

    if ($username === '' || $email === '' || $phone === '' || $password === '' || $passwordConfirm === '' || $captcha === '') {
        flash('danger', 'Заполните все поля.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('danger', 'Некорректный email.');
    } elseif (!preg_match('/^\+?[0-9\-\s]{10,20}$/', $phone)) {
        flash('danger', 'Некорректный номер телефона.');
    } elseif ($password !== $passwordConfirm) {
        flash('danger', 'Пароли не совпадают.');
    } elseif ((int) $captcha !== (int) ($_SESSION['captcha'] ?? 0)) {
        flash('danger', 'Неверная капча.');
    } else {
        $stmt = db()->prepare('SELECT id FROM users WHERE email = :email OR username = :username');
        $stmt->execute(['email' => $email, 'username' => $username]);

        if ($stmt->fetch()) {
            flash('danger', 'Пользователь с таким email или логином уже существует.');
        } else {
            $insert = db()->prepare('INSERT INTO users (username, email, phone, password_hash, role) VALUES (:username, :email, :phone, :password_hash, :role)');
            $insert->execute([
                'username' => $username,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'role' => 'user',
            ]);

            $_SESSION['captcha'] = random_int(1000, 9999);
            flash('success', 'Регистрация прошла успешно. Теперь войдите в систему.');
            header('Location: ' . url('/login.php'));
            exit;
        }
    }

    $_SESSION['captcha'] = random_int(1000, 9999);
}

require_once __DIR__ . '/../templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm soft-card">
            <div class="card-body p-4">
                <h2 class="mb-4">Регистрация</h2>
                <form method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Логин</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" placeholder="+7 900 123-45-67" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Повторите пароль</label>
                        <input type="password" name="password_confirm" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Капча: введите число <strong><?= (int) $_SESSION['captcha'] ?></strong></label>
                        <input type="text" name="captcha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

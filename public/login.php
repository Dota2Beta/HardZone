<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

if (isLoggedIn()) {
    header('Location: /index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        flash('danger', 'Заполните логин/email и пароль.');
    } else {
        $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = :login OR username = :login');
        $stmt->execute(['login' => $login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            flash('success', 'Вы вошли в систему.');
            header('Location: /index.php');
            exit;
        }

        flash('danger', 'Неверные данные для входа.');
    }
}

require_once __DIR__ . '/../templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-4">Вход</h2>
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Логин или email</label>
                        <input type="text" name="login" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Войти</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

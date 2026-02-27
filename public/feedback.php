<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

$user = currentUser();

if (empty($_SESSION['feedback_captcha'])) {
    $_SESSION['feedback_captcha'] = random_int(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $captcha = trim($_POST['captcha'] ?? '');

    if ($name === '' || $email === '' || $message === '' || $captcha === '') {
        flash('danger', 'Заполните все поля формы.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('danger', 'Укажите корректный email.');
    } elseif ((int) $captcha !== (int) ($_SESSION['feedback_captcha'] ?? 0)) {
        flash('danger', 'Неверная капча для обратной связи.');
    } else {
        $stmt = db()->prepare('INSERT INTO feedback (name, email, message) VALUES (:name, :email, :message)');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'message' => $message,
        ]);

        flash('success', 'Спасибо! Ваше сообщение отправлено.');
        $_SESSION['feedback_captcha'] = random_int(1000, 9999);
        header('Location: ' . url('/feedback.php'));
        exit;
    }

    $_SESSION['feedback_captcha'] = random_int(1000, 9999);
}

require_once __DIR__ . '/../templates/header.php';
?>
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card soft-card shadow-sm">
            <div class="card-body p-4">
                <h2 class="mb-3">Обратная связь</h2>
                <p class="text-secondary">Есть вопросы по заказу или нужна консультация по сборке ПК? Напишите нам.</p>
                <form method="post" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Ваше имя</label>
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Сообщение</label>
                        <textarea name="message" rows="5" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Капча: введите число <strong><?= (int) $_SESSION['feedback_captcha'] ?></strong></label>
                        <input type="text" name="captcha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="info-panel h-100">
            <h4>HardZone Support</h4>
            <p class="mb-2">Отвечаем обычно в течение 1 рабочего дня.</p>
            <ul>
                <li>Подбор компьютера под бюджет.</li>
                <li>Консультация по апгрейду.</li>
                <li>Информация по наличию и срокам доставки.</li>
            </ul>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

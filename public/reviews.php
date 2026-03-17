<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

$user = currentUser();

if (empty($_SESSION['reviews_captcha'])) {
    $_SESSION['reviews_captcha'] = random_int(1000, 9999);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$user) {
        flash('danger', 'Оставлять отзывы могут только зарегистрированные пользователи.');
        header('Location: ' . url('/login.php'));
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $rating = (int) ($_POST['rating'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $captcha = trim($_POST['captcha'] ?? '');

    if ($name === '' || $message === '' || $captcha === '' || $rating < 1 || $rating > 5) {
        flash('danger', 'Заполните корректно все поля отзыва.');
    } elseif ((int) $captcha !== (int) ($_SESSION['reviews_captcha'] ?? 0)) {
        flash('danger', 'Неверная капча для отзывов.');
    } else {
        $stmt = db()->prepare('INSERT INTO reviews (user_id, name, rating, message) VALUES (:user_id, :name, :rating, :message)');
        $stmt->execute([
            'user_id' => (int) $user['id'],
            'name' => $name,
            'rating' => $rating,
            'message' => $message,
        ]);

        flash('success', 'Спасибо! Ваш отзыв опубликован.');
        $_SESSION['reviews_captcha'] = random_int(1000, 9999);
        header('Location: ' . url('/reviews.php'));
        exit;
    }

    $_SESSION['reviews_captcha'] = random_int(1000, 9999);
}

$reviews = db()->query('SELECT id, name, rating, message, created_at FROM reviews ORDER BY id DESC')->fetchAll();

require_once __DIR__ . '/../templates/header.php';
?>
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card soft-card h-100">
            <div class="card-body p-4">
                <h1 class="mb-3">Отзывы</h1>
                <p class="text-secondary">Здесь публикуются реальные впечатления покупателей о сервисе и качестве наших сборок.</p>
                <?php if (!$user): ?>
                    <div class="alert alert-warning mb-0">
                        Оставлять отзывы могут только зарегистрированные пользователи.
                        <a href="<?= url('/login.php') ?>" class="alert-link">Войти</a>
                        или <a href="<?= url('/register.php') ?>" class="alert-link">зарегистрироваться</a>.
                    </div>
                <?php else: ?>
                    <form method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Ваше имя</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Оценка</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Выберите</option>
                                <option value="5">5 - Отлично</option>
                                <option value="4">4 - Хорошо</option>
                                <option value="3">3 - Нормально</option>
                                <option value="2">2 - Слабо</option>
                                <option value="1">1 - Плохо</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Текст отзыва</label>
                            <textarea name="message" rows="4" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Капча: введите число <strong><?= (int) $_SESSION['reviews_captcha'] ?></strong></label>
                            <input type="text" name="captcha" class="form-control" required>
                        </div>
                        <button class="btn btn-warning text-dark" type="submit">Опубликовать отзыв</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Последние отзывы</h2>
            <span class="text-secondary small">Всего: <?= count($reviews) ?></span>
        </div>
        <?php if (empty($reviews)): ?>
            <div class="card soft-card"><div class="card-body">Пока отзывов нет.</div></div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($reviews as $review): ?>
                    <div class="col-12">
                        <div class="card soft-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong><?= htmlspecialchars($review['name']) ?></strong>
                                    <span class="small text-secondary"><?= htmlspecialchars(date('d.m.Y', strtotime((string) $review['created_at']))) ?></span>
                                </div>
                                <div class="mb-2">Оценка: <?= str_repeat('⭐', (int) $review['rating']) ?></div>
                                <p class="mb-0 text-secondary"><?= nl2br(htmlspecialchars($review['message'])) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

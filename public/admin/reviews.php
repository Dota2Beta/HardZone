<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/config.php';
require_once __DIR__ . '/../../src/auth.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $reviewId = (int) ($_POST['review_id'] ?? 0);

    if ($action === 'delete' && $reviewId > 0) {
        $stmt = db()->prepare('DELETE FROM reviews WHERE id = :id');
        $stmt->execute(['id' => $reviewId]);
        flash('success', 'Отзыв удалён.');
        header('Location: ' . url('/admin/reviews.php'));
        exit;
    }

    if ($action === 'save' && $reviewId > 0) {
        $name = trim($_POST['name'] ?? '');
        $rating = (int) ($_POST['rating'] ?? 0);
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $message === '' || $rating < 1 || $rating > 5) {
            flash('danger', 'Проверьте поля отзыва.');
        } else {
            $stmt = db()->prepare('UPDATE reviews SET name = :name, rating = :rating, message = :message WHERE id = :id');
            $stmt->execute([
                'id' => $reviewId,
                'name' => $name,
                'rating' => $rating,
                'message' => $message,
            ]);
            flash('success', 'Отзыв обновлён.');
        }

        header('Location: ' . url('/admin/reviews.php'));
        exit;
    }
}

$reviews = db()->query('SELECT id, name, rating, message, created_at FROM reviews ORDER BY id DESC')->fetchAll();
$editId = (int) ($_GET['edit_id'] ?? 0);

require_once __DIR__ . '/../../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Модерация отзывов</h2>
    <a href="<?= url('/admin/index.php') ?>" class="btn btn-outline-light">Назад в админку</a>
</div>

<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr><th>ID</th><th>Имя</th><th>Оценка</th><th>Текст</th><th>Дата</th><th>Действия</th></tr>
        </thead>
        <tbody>
        <?php foreach ($reviews as $review): ?>
            <?php if ($editId === (int) $review['id']): ?>
                <tr>
                    <form method="post">
                        <td><?= (int) $review['id'] ?><input type="hidden" name="review_id" value="<?= (int) $review['id'] ?>"><input type="hidden" name="action" value="save"></td>
                        <td><input class="form-control" name="name" value="<?= htmlspecialchars($review['name']) ?>" required></td>
                        <td>
                            <select class="form-select" name="rating" required>
                                <?php for ($r = 5; $r >= 1; $r--): ?>
                                    <option value="<?= $r ?>" <?= (int) $review['rating'] === $r ? 'selected' : '' ?>><?= $r ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td><textarea class="form-control" name="message" rows="2" required><?= htmlspecialchars($review['message']) ?></textarea></td>
                        <td><?= htmlspecialchars((string) $review['created_at']) ?></td>
                        <td>
                            <button class="btn btn-sm btn-success" type="submit">Сохранить</button>
                            <a class="btn btn-sm btn-secondary" href="<?= url('/admin/reviews.php') ?>">Отмена</a>
                        </td>
                    </form>
                </tr>
            <?php else: ?>
                <tr>
                    <td><?= (int) $review['id'] ?></td>
                    <td><?= htmlspecialchars($review['name']) ?></td>
                    <td><?= (int) $review['rating'] ?></td>
                    <td><?= nl2br(htmlspecialchars($review['message'])) ?></td>
                    <td><?= htmlspecialchars((string) $review['created_at']) ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="<?= url('/admin/reviews.php') ?>?edit_id=<?= (int) $review['id'] ?>">Редактировать</a>
                        <form method="post" class="d-inline" onsubmit="return confirm('Удалить отзыв?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="review_id" value="<?= (int) $review['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger" type="submit">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

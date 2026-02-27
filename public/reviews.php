<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';

$reviews = [];
try {
    $stmt = db()->query('SELECT name, message, created_at FROM feedback ORDER BY id DESC LIMIT 20');
    $reviews = $stmt->fetchAll();
} catch (Throwable) {
    $reviews = [];
}

require_once __DIR__ . '/../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Отзывы клиентов</h1>
        <p class="text-secondary mb-0">Реальные сообщения из формы обратной связи.</p>
    </div>
    <a href="<?= url('/feedback.php') ?>" class="btn btn-warning text-dark">Оставить отзыв</a>
</div>

<?php if (empty($reviews)): ?>
    <div class="card soft-card">
        <div class="card-body">Пока отзывов нет. Будьте первым, кто оставит сообщение.</div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($reviews as $review): ?>
            <div class="col-md-6">
                <div class="card soft-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <strong><?= htmlspecialchars($review['name']) ?></strong>
                            <span class="small text-secondary"><?= htmlspecialchars(date('d.m.Y', strtotime((string) $review['created_at']))) ?></span>
                        </div>
                        <p class="mb-0 text-secondary"><?= nl2br(htmlspecialchars($review['message'])) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

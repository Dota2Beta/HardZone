<?php

declare(strict_types=1);

http_response_code(301);

require_once __DIR__ . '/../../templates/header.php';
?>
<section class="soft-card p-4 p-md-5 text-center">
    <h1 class="display-6 fw-bold mb-3">301 — Перемещено навсегда</h1>
    <p class="text-secondary mb-4">Эта страница показывает код 301 для отладки и демонстрации служебных ответов.</p>
    <a class="btn btn-warning text-dark" href="<?= url('/index.php') ?>">Вернуться на главную</a>
</section>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

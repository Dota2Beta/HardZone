<?php

declare(strict_types=1);

http_response_code(404);

require_once __DIR__ . '/../../templates/header.php';
?>
<section class="soft-card p-4 p-md-5 text-center">
    <h1 class="display-5 fw-bold mb-3">404 — Страница не найдена</h1>
    <p class="text-secondary mb-4">Похоже, ссылка устарела или страница была перемещена. Вернитесь на главную или откройте каталог.</p>
    <div class="d-flex justify-content-center gap-2 flex-wrap">
        <a class="btn btn-warning text-dark" href="<?= url('/index.php') ?>">На главную</a>
        <a class="btn btn-outline-light" href="<?= url('/catalog.php') ?>">Перейти в каталог</a>
    </div>
</section>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

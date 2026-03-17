<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/product.php';

$featuredProducts = array_slice(allProducts(), 0, 2);

require_once __DIR__ . '/../templates/header.php';
?>
<section class="hz-hero p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="chip">главная страница</span>
            <h1 class="display-5 fw-bold mt-3 mb-3">HardZone — премиальный онлайн-магазин компьютеров</h1>
            <p class="lead mb-4">Подберите идеальный ПК под игры, работу и творчество: мы собрали экспертные рекомендации, удобную навигацию и быстрый доступ к лучшим предложениям.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= url('/catalog.php') ?>" class="btn btn-warning text-dark fw-semibold">Перейти в каталог</a>
                <a href="<?= url('/feedback.php') ?>" class="btn btn-outline-light">Задать вопрос</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hz-side-card">
                <h5>Экспертные рекомендации перед покупкой</h5>
                <ul class="mb-0">
                    <li>Для учебы и офиса: оптимально от 16 GB RAM и SSD.</li>
                    <li>Для игр: в первую очередь ориентируйтесь на видеокарту и систему охлаждения.</li>
                    <li>Для дизайна и монтажа: важны многоядерный процессор, быстрый SSD и запас оперативной памяти.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="feature-box">📌 Шаг 1: определите комфортный бюджет</div></div>
    <div class="col-md-4"><div class="feature-box">🧩 Шаг 2: выбирайте конфигурацию под ваши задачи</div></div>
    <div class="col-md-4"><div class="feature-box">🛠️ Шаг 3: оставьте запас мощности на будущее</div></div>
</div>

<div class="card soft-card mb-4">
    <div class="card-body p-4">
        <h2 class="mb-3">Как получить максимум от HardZone</h2>
        <ol class="mb-0">
            <li>Откройте <strong>Каталог</strong>, сравните характеристики и выберите подходящую серию.</li>
            <li>Создайте аккаунт, чтобы отслеживать покупки и персонализировать профиль.</li>
            <li>Нужна помощь? Напишите в <strong>Обратную связь</strong> — подскажем оптимальный вариант.</li>
        </ol>
    </div>
</div>

<h2 class="mb-4">Рекомендуемые модели от экспертов HardZone</h2>
<div class="row g-4">
    <?php foreach ($featuredProducts as $product): ?>
        <div class="col-md-6">
            <div class="card h-100 product-card shadow-sm">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($product['description']) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-warning"><?= number_format((float) $product['price'], 0, '.', ' ') ?> ₽</span>
                        <a href="<?= url('/catalog.php') ?>" class="btn btn-sm btn-outline-light">Смотреть в каталоге</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

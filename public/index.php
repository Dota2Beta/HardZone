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
            <h1 class="display-5 fw-bold mt-3 mb-3">Добро пожаловать в HardZone</h1>
            <p class="lead mb-4">На этой странице собраны рекомендации по сайту и быстрый старт: как выбрать компьютер, где посмотреть весь каталог и как связаться с поддержкой.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= url('/catalog.php') ?>" class="btn btn-warning text-dark fw-semibold">Перейти в каталог</a>
                <a href="<?= url('/feedback.php') ?>" class="btn btn-outline-light">Задать вопрос</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hz-side-card">
                <h5>Полезно знать перед покупкой</h5>
                <ul class="mb-0">
                    <li>Для учебы: минимум 16 GB RAM.</li>
                    <li>Для игр: смотрите на видеокарту в первую очередь.</li>
                    <li>Для работы с графикой: важны RAM + SSD + многоядерный CPU.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="feature-box">📌 Совет 1: сначала определите бюджет</div></div>
    <div class="col-md-4"><div class="feature-box">🧩 Совет 2: выбирайте ПК под задачи</div></div>
    <div class="col-md-4"><div class="feature-box">🛠️ Совет 3: оставьте запас для апгрейда</div></div>
</div>

<div class="card soft-card mb-4">
    <div class="card-body p-4">
        <h2 class="mb-3">Рекомендации по сайту</h2>
        <ol class="mb-0">
            <li>Сначала откройте <strong>Каталог</strong> и сравните модели.</li>
            <li>Зарегистрируйтесь, чтобы редактировать профиль и загрузить аватар.</li>
            <li>Если нужна консультация — используйте раздел <strong>Обратная связь</strong>.</li>
        </ol>
    </div>
</div>

<h2 class="mb-4">Рекомендуемые компьютеры</h2>
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

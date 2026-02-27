<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/product.php';

$products = allProducts();

require_once __DIR__ . '/../templates/header.php';
?>
<section class="hz-hero p-4 p-md-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="chip">магазин компьютеров</span>
            <h1 class="display-5 fw-bold mt-3 mb-3">Собираем быстрые ПК для учёбы, игр и работы</h1>
            <p class="lead mb-4">HardZone — это готовые конфигурации и подбор под ваш бюджет. Никакой "пустоты": только техника, характеристики и помощь в выборе.</p>
            <div class="d-flex gap-2 flex-wrap">
                <a href="<?= url('/register.php') ?>" class="btn btn-warning text-dark fw-semibold">Начать с регистрации</a>
                <a href="<?= url('/feedback.php') ?>" class="btn btn-outline-light">Получить консультацию</a>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="hz-side-card">
                <h5>Почему HardZone?</h5>
                <ul class="mb-0">
                    <li>Проверенные комплектующие</li>
                    <li>Гарантия и поддержка</li>
                    <li>Доставка по России</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="feature-box">🚀 Быстрая отправка</div></div>
    <div class="col-md-4"><div class="feature-box">💳 Удобная оплата</div></div>
    <div class="col-md-4"><div class="feature-box">🧠 Помощь по подбору</div></div>
</div>

<h2 class="mb-4">Каталог компьютеров</h2>
<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100 product-card shadow-sm">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($product['description']) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-warning"><?= number_format((float) $product['price'], 0, '.', ' ') ?> ₽</span>
                        <span class="badge text-bg-dark">Остаток: <?= (int) $product['stock'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

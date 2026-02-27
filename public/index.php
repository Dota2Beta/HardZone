<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/product.php';

$products = allProducts();

require_once __DIR__ . '/../templates/header.php';
?>
<section class="hero p-5 mb-4 rounded-4 text-white shadow-lg">
    <div class="container-fluid py-3">
        <span class="badge bg-light text-dark mb-3">Интернет-магазин компьютеров</span>
        <h1 class="display-5 fw-bold">HardZone — мощные ПК для учебы, игр и работы</h1>
        <p class="col-md-8 fs-5">Собери идеальный компьютер или выбери готовое решение. Качественные комплектующие, гарантия и живые консультации.</p>
        <a href="<?= url('/feedback.php') ?>" class="btn btn-warning fw-semibold">Нужна консультация</a>
    </div>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="mini-stat">🚚 Быстрая доставка по РФ</div></div>
    <div class="col-md-4"><div class="mini-stat">🛡️ Гарантия до 36 месяцев</div></div>
    <div class="col-md-4"><div class="mini-stat">🔧 Поддержка и апгрейд</div></div>
</div>

<h2 class="mb-4">Каталог компьютеров</h2>
<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm product-card">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($product['description']) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 text-primary"><?= number_format((float) $product['price'], 0, '.', ' ') ?> ₽</span>
                        <span class="badge bg-secondary">Остаток: <?= (int) $product['stock'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

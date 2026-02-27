<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/product.php';

$products = allProducts();

require_once __DIR__ . '/../templates/header.php';
?>
<section class="hero p-5 mb-4 rounded-3 text-white">
    <div class="container-fluid py-3">
        <h1 class="display-5 fw-bold">HardZone — мощные ПК для учебы, игр и работы</h1>
        <p class="col-md-8 fs-5">Собери идеальный компьютер или выбери готовое решение. Качественные комплектующие и адекватные цены.</p>
    </div>
</section>

<h2 class="mb-4">Каталог компьютеров</h2>
<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
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

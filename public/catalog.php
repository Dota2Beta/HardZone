<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/cart.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    addToCart((int) $_POST['product_id'], max(1, (int) ($_POST['qty'] ?? 1)));
    flash('success', 'Товар добавлен в корзину.');
    header('Location: ' . url('/catalog.php'));
    exit;
}

$products = allProducts();

require_once __DIR__ . '/../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Каталог компьютеров</h1>
        <p class="text-secondary mb-0">Все доступные модели HardZone в одном месте.</p>
    </div>
    <a href="<?= url('/cart.php') ?>" class="btn btn-warning text-dark">Корзина (<?= cartCount() ?>)</a>
</div>

<div class="row g-4">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4">
            <div class="card h-100 product-card shadow-sm">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" class="card-img-top product-img" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                    <p class="card-text text-muted"><?= htmlspecialchars($product['description']) ?></p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold fs-5 text-warning"><?= number_format((float) $product['price'], 0, '.', ' ') ?> ₽</span>
                            <span class="badge text-bg-dark">Остаток: <?= (int) $product['stock'] ?></span>
                        </div>
                        <form method="post" class="d-flex gap-2">
                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                            <input type="number" min="1" max="<?= (int) $product['stock'] ?>" name="qty" value="1" class="form-control" style="max-width:90px">
                            <button type="submit" class="btn btn-outline-light flex-grow-1">В корзину</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

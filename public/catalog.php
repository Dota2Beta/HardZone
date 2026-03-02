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

$search = trim($_GET['search'] ?? '');
$minPrice = (float) ($_GET['min_price'] ?? 0);
$maxPrice = (float) ($_GET['max_price'] ?? 0);
$sort = (string) ($_GET['sort'] ?? 'new');

$products = filterProducts($search, $minPrice, $maxPrice, $sort);

require_once __DIR__ . '/../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="mb-1">Каталог компьютеров</h1>
        <p class="text-secondary mb-0">Все доступные модели HardZone в одном месте.</p>
    </div>
    <a href="<?= url('/cart.php') ?>" class="btn btn-warning text-dark">Корзина (<?= cartCount() ?>)</a>
</div>

<div class="card soft-card mb-4">
    <div class="card-body p-3">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Поиск</label>
                <input type="text" name="search" class="form-control" placeholder="Название или описание" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Цена от</label>
                <input type="number" min="0" step="1" name="min_price" class="form-control" value="<?= $minPrice > 0 ? htmlspecialchars((string)$minPrice) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Цена до</label>
                <input type="number" min="0" step="1" name="max_price" class="form-control" value="<?= $maxPrice > 0 ? htmlspecialchars((string)$maxPrice) : '' ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Сортировка</label>
                <select name="sort" class="form-select">
                    <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала новые</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Цена ↑</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Цена ↓</option>
                    <option value="stock_desc" <?= $sort === 'stock_desc' ? 'selected' : '' ?>>Больше в наличии</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-outline-light w-100">Фильтр</button>
                <a href="<?= url('/catalog.php') ?>" class="btn btn-secondary">Сброс</a>
            </div>
        </form>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="card soft-card"><div class="card-body">По выбранным фильтрам товаров не найдено.</div></div>
<?php else: ?>
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
<?php endif; ?>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/config.php';
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/product.php';

requireAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    deleteProduct((int) $_POST['delete_id']);
    flash('success', 'Товар удален.');
    header('Location: /admin/index.php');
    exit;
}

$products = allProducts();

require_once __DIR__ . '/../../templates/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Админ-панель: товары</h2>
    <a href="/admin/product_form.php" class="btn btn-primary">Добавить товар</a>
</div>
<div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>ID</th><th>Название</th><th>Цена</th><th>Остаток</th><th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= (int) $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= number_format((float) $product['price'], 0, '.', ' ') ?> ₽</td>
                <td><?= (int) $product['stock'] ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="/admin/product_form.php?id=<?= (int) $product['id'] ?>">Редактировать</a>
                    <form method="post" class="d-inline" onsubmit="return confirm('Удалить товар?')">
                        <input type="hidden" name="delete_id" value="<?= (int) $product['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

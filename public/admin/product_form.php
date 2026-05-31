<?php

declare(strict_types=1);

require_once __DIR__ . '/../../src/config.php';
require_once __DIR__ . '/../../src/auth.php';
require_once __DIR__ . '/../../src/product.php';
require_once __DIR__ . '/../../src/upload.php';

requireAdmin();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$product = $id > 0 ? getProduct($id) : null;

if ($id > 0 && !$product) {
    flash('danger', 'Товар не найден.');
    header('Location: ' . url('/admin/index.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $currentImage = $product['image_url'] ?? '';
        $uploadedImage = handleImageUpload('product_image', 'uploads/products');
        $imageUrlInput = trim($_POST['image_url'] ?? '');

        $finalImage = $currentImage;
        if ($uploadedImage !== null) {
            $finalImage = $uploadedImage;
        } elseif ($imageUrlInput !== '') {
            $finalImage = $imageUrlInput;
        }

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'image_url' => $finalImage,
            'stock' => (int) ($_POST['stock'] ?? 0),
        ];

        if ($data['name'] === '' || $data['description'] === '' || $data['price'] <= 0 || $data['image_url'] === '' || $data['stock'] < 0) {
            throw new RuntimeException('Заполните форму корректно и укажите изображение (ссылка или файл).');
        }

        if ($id > 0) {
            updateProduct($id, $data);
            flash('success', 'Товар обновлен.');
        } else {
            createProduct($data);
            flash('success', 'Товар добавлен.');
        }

        header('Location: ' . url('/admin/index.php'));
        exit;
    } catch (RuntimeException $e) {
        flash('danger', $e->getMessage());
    }
}

require_once __DIR__ . '/../../templates/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm soft-card">
            <div class="card-body p-4">
                <h2 class="mb-3"><?= $id > 0 ? 'Редактирование товара' : 'Добавление товара' ?></h2>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3"><label class="form-label">Название</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required></div>
                    <div class="mb-3"><label class="form-label">Описание</label><textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description'] ?? '') ?></textarea></div>
                    <div class="mb-3"><label class="form-label">Цена</label><input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars((string) ($product['price'] ?? '')) ?>" required></div>

                    <div class="mb-3">
                        <label class="form-label">Ссылка на изображение (URL)</label>
                        <input type="url" name="image_url" class="form-control" value="<?= htmlspecialchars($product['image_url'] ?? '') ?>" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Или загрузите файл (JPG/PNG/WEBP)</label>
                        <input type="file" name="product_image" class="form-control" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                        <div class="form-text text-secondary">Если заполнены оба поля, приоритет у загруженного файла.</div>
                    </div>

                    <?php if (!empty($product['image_url'])): ?>
                        <div class="mb-3"><img src="<?= htmlspecialchars($product['image_url']) ?>" class="admin-preview" alt="preview"></div>
                    <?php endif; ?>
                    <div class="mb-3"><label class="form-label">Остаток</label><input type="number" name="stock" class="form-control" value="<?= htmlspecialchars((string) ($product['stock'] ?? '0')) ?>" required></div>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                    <a href="<?= url('/admin/index.php') ?>" class="btn btn-secondary">Назад</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../templates/footer.php'; ?>

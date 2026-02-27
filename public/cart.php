<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';
require_once __DIR__ . '/../src/cart.php';

$user = currentUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update') {
        foreach ($_POST['qty'] ?? [] as $productId => $qty) {
            updateCartItem((int) $productId, (int) $qty);
        }
        flash('success', 'Корзина обновлена.');
        header('Location: ' . url('/cart.php'));
        exit;
    }

    if ($_POST['action'] === 'checkout') {
        $items = cartDetailedItems();
        if (empty($items)) {
            flash('danger', 'Корзина пуста.');
            header('Location: ' . url('/cart.php'));
            exit;
        }

        $customerName = trim($_POST['customer_name'] ?? '');
        $customerEmail = trim($_POST['customer_email'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? '');

        if ($customerName === '' || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            flash('danger', 'Укажите корректные имя и email для оплаты.');
            header('Location: ' . url('/cart.php'));
            exit;
        }

        if (!in_array($paymentMethod, ['card', 'sbp', 'cash'], true)) {
            flash('danger', 'Выберите способ оплаты.');
            header('Location: ' . url('/cart.php'));
            exit;
        }

        try {
            db()->exec('CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                customer_name VARCHAR(120) NOT NULL,
                customer_email VARCHAR(120) NOT NULL,
                payment_method VARCHAR(20) NOT NULL,
                total_amount DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');
            db()->exec('CREATE TABLE IF NOT EXISTS order_items (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )');

            db()->beginTransaction();

            $insertOrder = db()->prepare('INSERT INTO orders (user_id, customer_name, customer_email, payment_method, total_amount) VALUES (:user_id, :customer_name, :customer_email, :payment_method, :total_amount)');
            $insertOrder->execute([
                'user_id' => $user['id'] ?? null,
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'payment_method' => $paymentMethod,
                'total_amount' => cartTotal(),
            ]);

            $orderId = (int) db()->lastInsertId();
            $insertItem = db()->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
            $updateStock = db()->prepare('UPDATE products SET stock = stock - :qty WHERE id = :id AND stock >= :qty');

            foreach ($items as $item) {
                $product = $item['product'];
                $qty = (int) $item['qty'];

                $updateStock->execute(['qty' => $qty, 'id' => $product['id']]);
                if ($updateStock->rowCount() === 0) {
                    throw new RuntimeException('Недостаточно товара на складе: ' . $product['name']);
                }

                $insertItem->execute([
                    'order_id' => $orderId,
                    'product_id' => $product['id'],
                    'quantity' => $qty,
                    'price' => $product['price'],
                ]);
            }

            db()->commit();
            clearCart();
            flash('success', 'Оплата прошла успешно! Заказ №' . $orderId . ' оформлен.');
        } catch (Throwable $e) {
            if (db()->inTransaction()) {
                db()->rollBack();
            }
            flash('danger', 'Ошибка оформления заказа: ' . $e->getMessage());
        }

        header('Location: ' . url('/cart.php'));
        exit;
    }
}

$items = cartDetailedItems();
$total = cartTotal();

require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="mb-4">Корзина</h1>
<?php if (empty($items)): ?>
    <div class="card soft-card"><div class="card-body">Ваша корзина пока пуста. <a href="<?= url('/catalog.php') ?>">Перейти в каталог</a>.</div></div>
<?php else: ?>
    <form method="post" class="card soft-card mb-4">
        <div class="card-body">
            <input type="hidden" name="action" value="update">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead><tr><th>Товар</th><th>Цена</th><th>Кол-во</th><th>Сумма</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product']['name']) ?></td>
                            <td><?= number_format((float) $item['product']['price'], 0, '.', ' ') ?> ₽</td>
                            <td><input class="form-control" style="max-width:100px" type="number" min="0" max="<?= (int) $item['product']['stock'] ?>" name="qty[<?= (int) $item['product']['id'] ?>]" value="<?= (int) $item['qty'] ?>"></td>
                            <td><?= number_format((float) $item['subtotal'], 0, '.', ' ') ?> ₽</td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button class="btn btn-outline-light" type="submit">Обновить корзину</button>
        </div>
    </form>

    <div class="card soft-card">
        <div class="card-body">
            <h4 class="mb-3">Оплата заказа</h4>
            <p class="mb-3">Итого к оплате: <strong><?= number_format($total, 0, '.', ' ') ?> ₽</strong></p>
            <form method="post" class="row g-3">
                <input type="hidden" name="action" value="checkout">
                <div class="col-md-4"><input type="text" name="customer_name" class="form-control" placeholder="Ваше имя" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required></div>
                <div class="col-md-4"><input type="email" name="customer_email" class="form-control" placeholder="Email для чека" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required></div>
                <div class="col-md-4">
                    <select class="form-select" name="payment_method" required>
                        <option value="">Способ оплаты</option>
                        <option value="card">Банковская карта</option>
                        <option value="sbp">СБП</option>
                        <option value="cash">При получении</option>
                    </select>
                </div>
                <div class="col-12"><button class="btn btn-warning text-dark" type="submit">Оплатить и оформить заказ</button></div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>

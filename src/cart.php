<?php

declare(strict_types=1);

require_once __DIR__ . '/product.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cartItemsRaw(): array
{
    return $_SESSION['cart'] ?? [];
}

function addToCart(int $productId, int $qty = 1): void
{
    if ($qty < 1) {
        $qty = 1;
    }

    $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
}

function updateCartItem(int $productId, int $qty): void
{
    if ($qty <= 0) {
        unset($_SESSION['cart'][$productId]);
        return;
    }

    $_SESSION['cart'][$productId] = $qty;
}

function clearCart(): void
{
    unset($_SESSION['cart']);
}

function cartDetailedItems(): array
{
    $rows = [];
    foreach (cartItemsRaw() as $productId => $qty) {
        $product = getProduct((int) $productId);
        if (!$product) {
            continue;
        }

        $qty = min((int) $qty, (int) $product['stock']);
        if ($qty <= 0) {
            continue;
        }

        $subtotal = (float) $product['price'] * $qty;

        $rows[] = [
            'product' => $product,
            'qty' => $qty,
            'subtotal' => $subtotal,
        ];
    }

    return $rows;
}

function cartTotal(): float
{
    $sum = 0.0;
    foreach (cartDetailedItems() as $item) {
        $sum += $item['subtotal'];
    }

    return $sum;
}

function cartCount(): int
{
    $count = 0;
    foreach (cartItemsRaw() as $qty) {
        $count += (int) $qty;
    }

    return $count;
}

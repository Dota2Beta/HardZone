<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function allProducts(): array
{
    return db()->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
}

function getProduct(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);

    $product = $stmt->fetch();

    return $product ?: null;
}

function createProduct(array $data): void
{
    $stmt = db()->prepare('INSERT INTO products (name, description, price, image_url, stock) VALUES (:name, :description, :price, :image_url, :stock)');
    $stmt->execute($data);
}

function updateProduct(int $id, array $data): void
{
    $data['id'] = $id;
    $stmt = db()->prepare('UPDATE products SET name = :name, description = :description, price = :price, image_url = :image_url, stock = :stock WHERE id = :id');
    $stmt->execute($data);
}

function deleteProduct(int $id): void
{
    $stmt = db()->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
}

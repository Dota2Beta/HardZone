<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function allProducts(): array
{
    return db()->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
}

function filterProducts(string $search = '', float $minPrice = 0, float $maxPrice = 0, string $sort = 'new'): array
{
    $sql = 'SELECT * FROM products WHERE 1=1';
    $params = [];

    if ($search !== '') {
        $sql .= ' AND (name LIKE :search OR description LIKE :search)';
        $params['search'] = '%' . $search . '%';
    }

    if ($minPrice > 0) {
        $sql .= ' AND price >= :min_price';
        $params['min_price'] = $minPrice;
    }

    if ($maxPrice > 0) {
        $sql .= ' AND price <= :max_price';
        $params['max_price'] = $maxPrice;
    }

    $orderBy = match ($sort) {
        'price_asc' => 'price ASC',
        'price_desc' => 'price DESC',
        'stock_desc' => 'stock DESC',
        default => 'id DESC',
    };

    $sql .= ' ORDER BY ' . $orderBy;

    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
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

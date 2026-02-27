CREATE DATABASE IF NOT EXISTS hardzone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hardzone;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    avatar_path VARCHAR(255) DEFAULT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, phone, password_hash, role)
VALUES
('admin', 'admin@hardzone.local', '+79990000000', '$2y$12$6GNwht2BpV5OwfIZZHPv/.jbDQnA7LFYYhHIFDbbz9JCHXwE7HGk2', 'admin')
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO products (name, description, price, image_url, stock)
VALUES
('HardZone Starter', 'AMD Ryzen 5, 16GB RAM, SSD 512GB, RTX 4060', 89990, 'https://images.unsplash.com/photo-1593642702821-c8da6771f0c6?auto=format&fit=crop&w=900&q=80', 7),
('HardZone Pro', 'Intel Core i7, 32GB RAM, SSD 1TB, RTX 4070 Ti', 149990, 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&w=900&q=80', 4),
('HardZone Ultra', 'Ryzen 9, 64GB RAM, SSD 2TB, RTX 4090', 259990, 'https://images.unsplash.com/photo-1624705002806-5d72df19c3ab?auto=format&fit=crop&w=900&q=80', 2)
ON DUPLICATE KEY UPDATE name = VALUES(name);

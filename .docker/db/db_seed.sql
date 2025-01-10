CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
    id INT PRIMARY KEY,
    sku VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    price INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX index_category_id (category_id)
);

CREATE TABLE IF NOT EXISTS discounts (
    id INT PRIMARY KEY,
    type ENUM('category', 'product') NOT NULL,
    target_id INT NOT NULL,
    percentage INT NOT NULL,
    INDEX index_type_target_id (type, target_id)
);

INSERT INTO categories (id, name) VALUES
    (1, 'boots'),
    (2, 'sandals'),
    (3, 'sneakers');

INSERT INTO products (id, sku, name, category_id, price) VALUES
     (1, '000001', 'BV Lean leather ankle boots', 1, 89000),
     (2, '000002', 'BV Lean leather ankle boots', 1, 99000),
     (3, '000003', 'Ashlington leather ankle boots', 1, 71000),
     (4, '000004', 'Naima embellished suede sandals', 2, 79500),
     (5, '000005', 'Nathane leather sneakers', 3, 59000);

INSERT INTO discounts (id, type, target_id, percentage) VALUES
    (1, 'category', 1, 30),
    (2, 'product', 3, 15);
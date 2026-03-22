-- Database initialization for Giftbox Configurator
CREATE DATABASE IF NOT EXISTS giftbox CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE giftbox;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  vorname VARCHAR(100) NOT NULL,
  nachname VARCHAR(100) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  postleitzahl VARCHAR(10) NOT NULL,
  ort VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  price DECIMAL(8,2) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS configurations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  box_size VARCHAR(10),
  box_style VARCHAR(50),
  message TEXT,
  packaging VARCHAR(50),
  ribbon_color VARCHAR(50),
  total_price DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS configuration_products (
  configuration_id INT NOT NULL,
  product_id INT NOT NULL,
  PRIMARY KEY (configuration_id, product_id),
  FOREIGN KEY (configuration_id) REFERENCES configurations(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS coupons (
  code VARCHAR(50) PRIMARY KEY,
  discount_percent INT NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS preconfigs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  box_size VARCHAR(10),
  box_style VARCHAR(50),
  products TEXT
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Insert sample products
INSERT INTO products (name, price) VALUES
('Schokolade', 3.50),
('Tasse', 6.50),
('Tee', 4.50),
('Kaffee', 5.00),
('Kerze', 6.00),
('Badebombe', 3.80),
('Kekse', 2.90),
('Honig', 7.50),
('Marmelade', 4.20),
('Fruchtgummi', 1.20),
('Chips', 1.80),
('Proteinriegel', 2.50),
('Energy Drink', 2.00),
('Duftkerze', 8.00),
('Seife', 2.70),
('Lippenbalsam', 3.00),
('Mini-Parfum', 12.00),
('Notizbuch', 5.50),
('Popcorn', 2.50),
('Sticker', 1.00)
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- Coupon
INSERT INTO coupons (code, discount_percent) VALUES ('SAVE10', 10)
ON DUPLICATE KEY UPDATE discount_percent=VALUES(discount_percent);

-- Preconfigured boxes (product IDs as comma-separated)
INSERT INTO preconfigs (name, box_size, box_style, products) VALUES
('Geburtstagsbox', 'M', 'Geburtstag', '1,7,18,19'),
('Wellnessbox', 'M', 'Neutral', '5,6,14,15'),
('Snackbox', 'L', 'Neutral', '2,11,12,13')
ON DUPLICATE KEY UPDATE name=VALUES(name);

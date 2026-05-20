-- Bazar Mix da Jô — Estrutura do banco de dados
--
-- IMPORTANTE (Hostinger / cPanel):
-- 1. Crie o banco "u921961937_bazar_mix_jo" pelo painel da Hostinger.
-- 2. Crie o usuário "u921961937_bazar_user" e vincule-o ao banco com todos
--    os privilégios.
-- 3. Abra o phpMyAdmin, selecione o banco criado e importe este arquivo
--    na aba "Importar". NÃO descomente as linhas CREATE DATABASE / USE
--    abaixo — na Hostinger o banco já existe quando o SQL é importado.
--
-- Para uso local (Docker / linha de comando), descomente as duas linhas:
-- CREATE DATABASE IF NOT EXISTS bazar_mix_jo
--   CHARACTER SET utf8mb4
--   COLLATE utf8mb4_unicode_ci;
-- USE bazar_mix_jo;

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(160) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_id INT UNSIGNED NULL,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_1 VARCHAR(255) NULL,
  image_2 VARCHAR(255) NULL,
  image_3 VARCHAR(255) NULL,
  image_4 VARCHAR(255) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_products_active (is_active),
  INDEX idx_products_price (price),
  INDEX idx_products_category (category_id),
  CONSTRAINT fk_products_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO admins (name, email, password_hash) VALUES
('Joelma', 'admin@bazarmixjo.com', '$2y$10$gujPRcmW.R5wAx9Uo10mhuczQJIAzoCYbQ.C/SVe8S1zoNbi.vPfC');

INSERT INTO categories (name, status) VALUES
('Roupas', 1),
('Sapatos', 1),
('Bolsas', 1),
('Acessórios', 1),
('Casa', 1),
('Infantil', 1),
('Diversos', 1),
('Eletrônicos', 1);

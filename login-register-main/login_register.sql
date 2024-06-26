-- Veritabanı oluşturma
CREATE DATABASE IF NOT EXISTS login_registration;

-- Kullanıcılar tablosu oluşturma
USE login_registration;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Örnek bir kullanıcı ekleyelim (opsiyonel)
INSERT INTO users (fullname, email, password) VALUES
('John Doe', 'john@gmail.com', 'test'), -- Şifre: password
('Doe', 'doe@gmail.com', 'test'),
('test', 'test@gmail.com', 'test'),
('test2', 'test2@gmail.com', 'test'),
('test3', 'test3@gmail.com', 'test');
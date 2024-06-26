-- Veritabanı oluşturma
CREATE DATABASE IF NOT EXISTS login_registration;

-- Kullanıcılar tablosu oluşturma
USE login_registration;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Örnek bir kullanıcı ekleyelim (opsiyonel)
-- Not: Şifreler password_hash ile hashlenmiş olmalıdır.
INSERT INTO users (full_name, email, password) VALUES
('John Doe', 'john@gmail.com', '$2y$10$eBHK/PZfJS5/uNkO8T.XKuFub/vq1so3gJoI2/Xu9duLP8rB0I1kW'), -- Şifre: password
('Doe', 'doe@gmail.com', '$2y$10$eBHK/PZfJS5/uNkO8T.XKuFub/vq1so3gJoI2/Xu9duLP8rB0I1kW'), -- Şifre: password
('test', 'test@gmail.com', '$2y$10$eBHK/PZfJS5/uNkO8T.XKuFub/vq1so3gJoI2/Xu9duLP8rB0I1kW'), -- Şifre: password
('test2', 'test2@gmail.com', '$2y$10$eBHK/PZfJS5/uNkO8T.XKuFub/vq1so3gJoI2/Xu9duLP8rB0I1kW'), -- Şifre: password
('test3', 'test3@gmail.com', '$2y$10$eBHK/PZfJS5/uNkO8T.XKuFub/vq1so3gJoI2/Xu9duLP8rB0I1kW'); -- Şifre: password

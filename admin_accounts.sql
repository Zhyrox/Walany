CREATE DATABASE IF NOT EXISTS walania;

USE walania;

CREATE TABLE IF NOT EXISTS admin_accounts (
    admin_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin') NOT NULL DEFAULT 'admin',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admin_accounts (full_name, email, password_hash, role)
VALUES
    (
        'Walania Admin',
        'admin@walania.test',
        '$2y$10$replaceThisWithAPasswordHashFromPhpPasswordHash',
        'super_admin'
    )
ON DUPLICATE KEY UPDATE
    full_name = VALUES(full_name),
    role = VALUES(role),
    updated_at = CURRENT_TIMESTAMP;

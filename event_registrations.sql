CREATE DATABASE IF NOT EXISTS walania;

USE walania;

CREATE TABLE IF NOT EXISTS event_registrations (
    registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    age TINYINT UNSIGNED NOT NULL,
    email VARCHAR(160) NOT NULL,
    contact_number VARCHAR(40) NOT NULL,
    event_name VARCHAR(120) NOT NULL,
    preference_allergy VARCHAR(255) NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

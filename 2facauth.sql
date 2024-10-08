DROP DATABASE IF EXISTS 2FA;

CREATE DATABASE 2FA;

USE 2FA;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    userpass VARCHAR(255) NOT NULL,
    emailOtp int(6) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Database schema for Online Food Helpline System

-- Create the database
CREATE DATABASE IF NOT EXISTS food_helpline;

-- Use the database
USE food_helpline;

-- Create the users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'receiver', 'volunteer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional: Insert a sample admin user (password: admin123)
INSERT INTO users (name, email, phone, password, role) VALUES
('Admin User', 'admin@foodhelpline.com', '1234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

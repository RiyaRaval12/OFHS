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

-- Create the donations table
CREATE TABLE IF NOT EXISTS donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_id INT NOT NULL,
    food_type VARCHAR(255) NOT NULL,
    quantity VARCHAR(255) NOT NULL,
    expiry_date DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('available', 'assigned', 'delivered') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create the food_requests table
CREATE TABLE IF NOT EXISTS food_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    receiver_id INT NOT NULL,
    food_type VARCHAR(255) NOT NULL,
    quantity_needed VARCHAR(255) NOT NULL,
    urgency ENUM('low', 'medium', 'high') DEFAULT 'medium',
    location VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('pending', 'assigned', 'fulfilled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create the assignments table
CREATE TABLE IF NOT EXISTS assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donation_id INT NOT NULL,
    request_id INT NOT NULL,
    volunteer_id INT NOT NULL,
    status ENUM('assigned', 'in_progress', 'completed') DEFAULT 'assigned',
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE CASCADE,
    FOREIGN KEY (request_id) REFERENCES food_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (volunteer_id) REFERENCES users(id) ON DELETE CASCADE
);

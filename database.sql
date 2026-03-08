-- Database schema for Online Food Helpline System
-- Run in MySQL 5.7+/MariaDB. Safe to re-run (uses IF NOT EXISTS).

-- Create the database
CREATE DATABASE IF NOT EXISTS food_helpline;

-- Use the database
USE food_helpline;

-- Users hold all roles (admin, donor, receiver, volunteer)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    organization VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'donor', 'receiver', 'volunteer') NOT NULL,
    profile_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Food listings created by donors and claimable by receivers/volunteers
CREATE TABLE IF NOT EXISTS food_listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,                -- donor who posted
    title VARCHAR(255) NOT NULL,
    description TEXT,
    quantity INT DEFAULT 1,
    unit VARCHAR(50) DEFAULT 'items',
    location VARCHAR(255) NOT NULL,
    expires_at DATETIME NULL,
    status ENUM('available','claimed','completed','expired') DEFAULT 'available',
    claimed_by INT NULL,                 -- receiver id
    volunteer_id INT NULL,               -- volunteer helping pickup
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (claimed_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (volunteer_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Assistance requests logged by receivers; volunteers can pick up/deliver
CREATE TABLE IF NOT EXISTS assistance_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    servings INT DEFAULT 1,
    address VARCHAR(255) NOT NULL,
    needed_by DATETIME NULL,
    status ENUM('open','picked_up','delivered','closed') DEFAULT 'open',
    volunteer_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (volunteer_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Activity log for simple impact counters
CREATE TABLE IF NOT EXISTS activity_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- volunteer or donor
    activity VARCHAR(50) NOT NULL, -- donation_created, request_fulfilled, delivery_completed
    ref_type ENUM('food_listing','assistance_request') NOT NULL,
    ref_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Seed users (password for all demo users: admin123)
INSERT INTO users (name, email, phone, address, organization, password, role, profile_completed) VALUES
('Admin User', 'admin@foodhelpline.com', '1234567890', '1 Admin Way', 'FoodHelpline HQ', '$2y$10$9WNeUs3rp7pqmwMW6nhgyuPQjK54N2H0ekRM0yLAjxDAPBYvbzwB.', 'admin', 1),
('Riya Raval', 'riya@foodhelpline.com', '9999999999', '123 Main St, City', NULL, '$2y$10$9WNeUs3rp7pqmwMW6nhgyuPQjK54N2H0ekRM0yLAjxDAPBYvbzwB.', 'receiver', 1),
('Alice Baker', 'alice@bakery.com', '8888888888', '123 Main St, City', 'Alice''s Bakery', '$2y$10$9WNeUs3rp7pqmwMW6nhgyuPQjK54N2H0ekRM0yLAjxDAPBYvbzwB.', 'donor', 1),
('Vik Volunteer', 'vik@help.com', '7777777777', '456 Elm St, City', NULL, '$2y$10$9WNeUs3rp7pqmwMW6nhgyuPQjK54N2H0ekRM0yLAjxDAPBYvbzwB.', 'volunteer', 1);

-- Seed food listings (all available)
INSERT INTO food_listings (user_id, title, description, quantity, unit, location, expires_at, status)
VALUES
(3, 'Fresh Bread Batch', 'Leftover baguettes and sourdough from today''s bake.', 15, 'loaves', '123 Main St, Alice''s Bakery', DATE_ADD(NOW(), INTERVAL 1 DAY), 'available'),
(3, 'Canned Vegetables', 'Surplus canned corn and peas.', 20, 'cans', '123 Main St, Alice''s Bakery', DATE_ADD(NOW(), INTERVAL 5 DAY), 'available');

-- Seed requests (one picked up by volunteer)
INSERT INTO assistance_requests (requester_id, title, description, servings, address, needed_by, status, volunteer_id)
VALUES
(2, 'Need food for a family of 4 for 2 days.', 'Staples, bread, veggies, anything helps.', 4, '456 Elm St', DATE_ADD(NOW(), INTERVAL 1 DAY), 'picked_up', 4);

-- Log one activity for demo "My Impact"
INSERT INTO activity_log (user_id, activity, ref_type, ref_id)
VALUES (4, 'delivery_completed', 'assistance_request', 1);

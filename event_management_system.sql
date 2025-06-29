-- Event Management System Database
-- MySQL Database Schema and Sample Data

-- Create database
CREATE DATABASE IF NOT EXISTS event_management_system;
USE event_management_system;

-- Drop tables if they exist (in reverse order of dependencies)
DROP TABLE IF EXISTS failed_jobs;
DROP TABLE IF EXISTS job_batches;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS cache_locks;
DROP TABLE IF EXISTS cache;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS password_reset_tokens;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Create password_reset_tokens table
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);

-- Create sessions table
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create events table
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    venue VARCHAR(255) NOT NULL,
    capacity INT NOT NULL,
    price DECIMAL(8,2) NOT NULL,
    image VARCHAR(255) NULL,
    status ENUM('active', 'inactive', 'cancelled') DEFAULT 'active',
    user_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create bookings table
CREATE TABLE bookings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    ticket_number VARCHAR(255) UNIQUE NOT NULL,
    qr_code TEXT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    quantity INT DEFAULT 1,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Create cache table
CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL
);

-- Create cache_locks table
CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL
);

-- Create jobs table
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX jobs_queue_index (queue)
);

-- Create job_batches table
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
);

-- Create failed_jobs table
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data

-- Sample users (password is 'password' hashed with bcrypt)
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW()),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW()),
('Mike Johnson', 'mike@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW());

-- Sample events
INSERT INTO events (title, description, date, time, venue, capacity, price, status, user_id, created_at, updated_at) VALUES
('Tech Conference 2024', 'Annual technology conference featuring the latest innovations in software development, AI, and cloud computing. Join industry experts for networking and learning opportunities.', '2024-12-15', '09:00:00', 'Convention Center, Downtown', 500, 299.99, 'active', 1, NOW(), NOW()),
('Music Festival', 'A three-day music festival featuring local and international artists. Food trucks, art installations, and family-friendly activities included.', '2024-11-20', '18:00:00', 'Central Park', 1000, 89.99, 'active', 1, NOW(), NOW()),
('Business Networking Event', 'Monthly networking event for professionals. Includes keynote speaker, refreshments, and structured networking sessions.', '2024-10-25', '19:00:00', 'Business Center, Midtown', 200, 49.99, 'active', 1, NOW(), NOW()),
('Art Exhibition Opening', 'Opening night of contemporary art exhibition featuring works from emerging artists. Wine and cheese reception included.', '2024-12-01', '20:00:00', 'Modern Art Gallery', 150, 25.00, 'active', 1, NOW(), NOW()),
('Workshop: Digital Marketing', 'Hands-on workshop covering SEO, social media marketing, and content strategy. Includes take-home materials and certificate.', '2024-11-10', '10:00:00', 'Learning Center', 50, 199.99, 'active', 1, NOW(), NOW());

-- Sample bookings
INSERT INTO bookings (user_id, event_id, ticket_number, qr_code, status, quantity, booking_date, created_at, updated_at) VALUES
(2, 1, 'TICKET-001-2024', 'QR_CODE_DATA_1', 'confirmed', 2, NOW(), NOW(), NOW()),
(3, 2, 'TICKET-002-2024', 'QR_CODE_DATA_2', 'confirmed', 1, NOW(), NOW(), NOW()),
(4, 3, 'TICKET-003-2024', 'QR_CODE_DATA_3', 'pending', 1, NOW(), NOW(), NOW()),
(2, 4, 'TICKET-004-2024', 'QR_CODE_DATA_4', 'confirmed', 3, NOW(), NOW(), NOW()),
(3, 5, 'TICKET-005-2024', 'QR_CODE_DATA_5', 'confirmed', 1, NOW(), NOW(), NOW()),
(4, 1, 'TICKET-006-2024', 'QR_CODE_DATA_6', 'cancelled', 1, NOW(), NOW(), NOW());

-- Create indexes for better performance
CREATE INDEX idx_events_date ON events(date);
CREATE INDEX idx_events_status ON events(status);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_booking_date ON bookings(booking_date);
CREATE INDEX idx_users_role ON users(role);

-- Show table structure
SHOW TABLES;

-- Show sample data
SELECT 'Users:' as info;
SELECT id, name, email, role FROM users;

SELECT 'Events:' as info;
SELECT id, title, date, venue, capacity, price, status FROM events;

SELECT 'Bookings:' as info;
SELECT b.id, u.name as user_name, e.title as event_title, b.ticket_number, b.status, b.quantity 
FROM bookings b 
JOIN users u ON b.user_id = u.id 
JOIN events e ON b.event_id = e.id; 
-- Car Rental System Database Schema

-- Create Database
CREATE DATABASE IF NOT EXISTS car_rental_system;
USE car_rental_system;

-- Users Table (for both customers and agencies)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('customer', 'agency') NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    -- Agency specific fields
    agency_name VARCHAR(255) DEFAULT NULL,
    license_number VARCHAR(100) DEFAULT NULL,
    -- Common fields
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    INDEX idx_email (email),
    INDEX idx_user_type (user_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cars Table
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agency_id INT NOT NULL,
    vehicle_model VARCHAR(255) NOT NULL,
    vehicle_number VARCHAR(50) NOT NULL UNIQUE,
    seating_capacity INT NOT NULL,
    rent_per_day DECIMAL(10, 2) NOT NULL,
    status ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agency_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_agency (agency_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    car_id INT NOT NULL,
    agency_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    rental_days INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    booking_status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    FOREIGN KEY (agency_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_customer (customer_id),
    INDEX idx_car (car_id),
    INDEX idx_agency (agency_id),
    INDEX idx_status (booking_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample agency user (password: agency123)
INSERT INTO users (user_type, email, password, full_name, phone, address, agency_name, license_number) 
VALUES ('agency', 'agency@test.com', 'agency123', 'Premium Car Rentals', '1234567890', '123 Main St, City', 'Premium Car Rentals', 'LIC001');

-- Insert sample customer user (password: customer123)
INSERT INTO users (user_type, email, password, full_name, phone, address) 
VALUES ('customer', 'customer@test.com', 'customer123', 'John Doe', '9876543210', '456 Oak Ave, Town');

-- Insert sample cars
INSERT INTO cars (agency_id, vehicle_model, vehicle_number, seating_capacity, rent_per_day, status) VALUES
(1, 'Toyota Camry 2023', 'ABC-1234', 5, 50.00, 'available'),
(1, 'Honda Accord 2023', 'XYZ-5678', 5, 55.00, 'available'),
(1, 'Ford Explorer 2023', 'DEF-9012', 7, 75.00, 'available'),
(1, 'Tesla Model 3', 'TES-3456', 5, 100.00, 'available');
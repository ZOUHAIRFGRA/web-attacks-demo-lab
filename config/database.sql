CREATE DATABASE IF NOT EXISTS security_demo;
USE security_demo;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100)
);

-- Insert some sample data
INSERT INTO users (username, password, email) VALUES
('admin', '123456', 'admin@example.com'),
('john', 'password123', 'john@example.com'),
('alice', 'alice123', 'alice@example.com');

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT
);

INSERT INTO products (name, price, description) VALUES
('Laptop', 999.99, 'High-performance laptop'),
('Smartphone', 499.99, 'Latest model smartphone'),
('Tablet', 299.99, 'Lightweight tablet');

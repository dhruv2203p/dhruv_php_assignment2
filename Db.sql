
CREATE DATABASE  ecommerce;


USE ecommerce;


CREATE TABLE IF NOT EXISTS perfumes (
    PerfumeID INT AUTO_INCREMENT PRIMARY KEY,
    PerfumeName VARCHAR(255) NOT NULL,
    PerfumeDescription TEXT NOT NULL,
    FragranceType VARCHAR(100) NOT NULL,
    Brand VARCHAR(100) NOT NULL,
    QuantityAvailable INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    ProductAddedBy VARCHAR(255) NOT NULL DEFAULT 'Dhruv Patel',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

<?php
$servername = "localhost";
$username = "root"; // Change to your MySQL username
$password = ""; // Change to your MySQL password
$dbname = "ecommerce";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    
} else {
    echo "Error creating database: " . $conn->error;
}

// Use the created database
$conn->select_db($dbname);

// Create table 'perfumes' with additional fields
$sql = "CREATE TABLE IF NOT EXISTS perfumes (
    PerfumeID INT AUTO_INCREMENT PRIMARY KEY,
    PerfumeName VARCHAR(255) NOT NULL,
    PerfumeDescription TEXT NOT NULL,
    FragranceType VARCHAR(100) NOT NULL,
    Brand VARCHAR(100) NOT NULL,
    QuantityAvailable INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    ProductAddedBy VARCHAR(255) NOT NULL default 'Dhruv Patel',
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {

} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>

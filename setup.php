<?php
$servername = "localhost";
$username = "root";
$password = "";

$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "CREATE DATABASE IF NOT EXISTS photo_impact";

if (mysqli_query($conn, $sql)) {
    echo "Database created successfully.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

mysqli_select_db($conn, "photo_impact");

$sql = "DROP TABLE IF EXISTS contact";
mysqli_query($conn, $sql);

$sql = "DROP TABLE IF EXISTS users";
mysqli_query($conn, $sql);

$sql = "CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(45) NOT NULL,
    lname VARCHAR(60) NOT NULL,
    email VARCHAR(70) NOT NULL UNIQUE,
    phone_number VARCHAR(20) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_hidden BOOLEAN NOT NULL DEFAULT FALSE,
    role ENUM('user', 'admin') DEFAULT 'user'
)";

if (mysqli_query($conn, $sql)) {
    echo "Users table created successfully.<br>";
} else {
    echo "Error creating users table: " . mysqli_error($conn) . "<br>";
}

$sql = "CREATE TABLE contact (
    contact_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(70) NOT NULL,
    is_user BOOLEAN DEFAULT TRUE,
    message TEXT NOT NULL,
    user_id INT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
)";

if (mysqli_query($conn, $sql)) {
    echo "Contact table created successfully.<br>";
} else {
    echo "Error creating contact table: " . mysqli_error($conn) . "<br>";
}

mysqli_close($conn);
?>
<?php
// inc/config.php

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Default XAMPP password is empty
define('DB_NAME', 'nutricalc');

// Base URL (Adjust if your folder name is different)
define('BASE_URL', 'http://localhost/Gizi_seimbang/');

// Path Constants
define('ROOT_PATH', dirname(__DIR__) . '/');

// Error Reporting (Turn off for production, on for development)
// error_reporting(0); // Production
error_reporting(E_ALL); // Development
ini_set('display_errors', 1);

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<?php

$db_host = 'DDDD'; // Use the correct hostname
$db_name = 'DDDD'; // Use your database name
$user_name = 'DDDD'; // Use your MySQL user name
$user_password = 'DDDD'; // Use your MySQL password

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable error mode to throw exceptions on errors.
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set the default fetch mode to associative arrays.
    PDO::ATTR_EMULATE_PREPARES => false, // Disable emulated prepared statements.
];

try {
    // Create a new PDO connection using the specified parameters.
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $user_name, $user_password);

    // Set the error mode for the connection to throw exceptions on errors.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If an exception is caught, print an error message including the exception message.
    echo "Connection failed: " . $e->getMessage();
}
?>
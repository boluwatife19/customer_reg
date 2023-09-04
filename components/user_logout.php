<?php
// Include the 'connect.php' file to access the database connection
include './connect.php';

// Start the PHP session
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the home page
header('location:../pages/home.php');
?>

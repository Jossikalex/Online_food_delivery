<?php
/**
 * DATABASE CONNECTION FILE
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Ella_delivery";

// Create connection
$connect = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$connect) {
    // Silently exit to show blank space if connection fails
    exit();
}

mysqli_set_charset($connect, "utf8mb4");
date_default_timezone_set('Africa/Addis_Ababa');

// Silence errors for the user
error_reporting(0);
ini_set('display_errors', 0);
?>
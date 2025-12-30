<?php
/**
 * DATABASE CONNECTION FILE
 * Ella Kitchen Cafe System
 */

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "Ella_delivery";

/* ✅ STANDARDIZED VARIABLE NAME */
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

/* Charset */
mysqli_set_charset($conn, "utf8mb4");

/* Timezone */
date_default_timezone_set('Africa/Addis_Ababa');

/* Debug (TURN OFF IN PRODUCTION) */
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

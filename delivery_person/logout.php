<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

/** * Redirect to the admin dashboard
 * Note: ../ moves out of the 'delivery_boy' folder so it can find 'admin/'
 */
header("Location: ../login.php");
exit();
?>
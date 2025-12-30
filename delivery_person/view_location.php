<?php
session_start();
require_once 'connection.php';

/* ===============================
   SECURITY: LOGIN + ROLE CHECK
   =============================== */
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'delivery'
) {
    header("Location: login.php");
    exit();
}

$deliveryId = (int) $_SESSION['user_id'];
$order_id   = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($order_id <= 0) {
    echo "Invalid order.";
    exit;
}

/* ===============================
   FETCH CUSTOMER LOCATION
   =============================== */
$sql = "
    SELECT 
        ST_Y(c.location) AS latitude,
        ST_X(c.location) AS longitude
    FROM orders o
    JOIN Customer c ON o.cust_id = c.cust_id
    WHERE o.order_id = ?
      AND o.del_id   = ?
    LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $order_id, $deliveryId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    $lat = $row['latitude'];
    $lng = $row['longitude'];

    if (!is_null($lat) && !is_null($lng)) {
        header("Location: https://www.google.com/maps?q={$lat},{$lng}");
        exit();
    }
}

echo "Customer location not available.";
exit;

<?php
session_start();
require_once "../connection.php"; // Added ../ to reach the root folder

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
    header("Location: ../login.php"); // Added ../ to reach login in root
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: assigned_order.php");
    exit();
}

$orderId    = (int) $_GET['id'];
$deliveryId = (int) $_SESSION['user_id'];

/* ✅ FIX: Table name changed to `Order` and status reset to 'ready' 
   (so other drivers can see it) */
$updateOrder = "
    UPDATE `Order`
    SET del_id = NULL,
        status = 'ready'
    WHERE order_id = ?
      AND del_id   = ?
";

$stmt1 = mysqli_prepare($connect, $updateOrder);
mysqli_stmt_bind_param($stmt1, "ii", $orderId, $deliveryId);
mysqli_stmt_execute($stmt1);

/* ✅ Update delivery person status to available */
$updateDelivery = "
    UPDATE Delivery_person
    SET status = 'available'
    WHERE del_id = ?
";

$stmt2 = mysqli_prepare($connect, $updateDelivery);
mysqli_stmt_bind_param($stmt2, "i", $deliveryId);
mysqli_stmt_execute($stmt2);

header("Location: assigned_order.php");
exit();
?>
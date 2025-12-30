<?php
session_start();
require_once "../connection.php"; // Path is correct if file is in delivery_boy folder

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: assigned_order.php");
    exit();
}

$orderId    = (int) $_GET['id'];
$deliveryId = (int) $_SESSION['user_id'];

/* ✅ FIX: Table name changed from orders to `Order` */
$updateOrder = "
    UPDATE `Order`
    SET status = 'out_for_delivery'
    WHERE order_id = ?
      AND del_id   = ?
";

$stmt1 = mysqli_prepare($connect, $updateOrder);
mysqli_stmt_bind_param($stmt1, "ii", $orderId, $deliveryId);
mysqli_stmt_execute($stmt1);

/* ✅ Update delivery person status */
$updateDelivery = "
    UPDATE Delivery_person
    SET status = 'busy'
    WHERE del_id = ?
";

$stmt2 = mysqli_prepare($connect, $updateDelivery);
mysqli_stmt_bind_param($stmt2, "i", $deliveryId);
mysqli_stmt_execute($stmt2);

// Make sure current_delivery.php exists, or redirect back to assigned_order.php
header("Location: current_delivery.php");
exit();
?>
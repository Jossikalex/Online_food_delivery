<?php
require_once 'connection.php';

$oid = (int)($_POST['order_id'] ?? 0);
$st  = mysqli_real_escape_string($connect, $_POST['status'] ?? '');

if ($oid && $st) {
    mysqli_query(
        $connect,
        "UPDATE `Order` SET status='$st' WHERE order_id=$oid"
    );
}

header("Location: orders.php");
exit;

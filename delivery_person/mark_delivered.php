<?php


// FORCE ERRORS TO SHOW
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



session_start();
require_once "../connection.php"; 

/* ===============================
    SECURITY: LOGIN + ROLE CHECK
   =============================== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
    header("Location: ../login.php");
    exit();
}

if (!$connect || !isset($_GET['id'])) {
    header("Location: current_delivery.php");
    exit();
}

$orderId    = (int) $_GET['id'];
$deliveryId = (int) $_SESSION['user_id'];

/* ===============================
    DATABASE TRANSACTION
   =============================== */
mysqli_begin_transaction($connect);

try {
    // 1. Fetch Order details (Column in Order table is cust_id)
    $fetchSql = "SELECT cust_id, total_amount FROM `Order` WHERE order_id = ? AND del_id = ? LIMIT 1";
    $stmtFetch = mysqli_prepare($connect, $fetchSql);
    mysqli_stmt_bind_param($stmtFetch, "ii", $orderId, $deliveryId);
    mysqli_stmt_execute($stmtFetch);
    $res = mysqli_stmt_get_result($stmtFetch);
    $orderData = mysqli_fetch_assoc($res);

    if ($orderData) {
        $custId = $orderData['cust_id'];
        $amount = $orderData['total_amount'];

        // 2. Insert into Sales Table 
       
        $sqlSales = "INSERT INTO Sales (order_id, customer_id, total_amount, sale_date) VALUES (?, ?, ?, NOW())";
        $stmtSales = mysqli_prepare($connect, $sqlSales);
        
        // "iid" = Integer (order_id), Integer (customer_id), Double (amount)
        mysqli_stmt_bind_param($stmtSales, "iid", $orderId, $custId, $amount);
        mysqli_stmt_execute($stmtSales);

        // 3. Update Order Status to 'delivered'
        $sqlOrder = "UPDATE `Order` SET status = 'delivered' WHERE order_id = ?";
        $stmtOrder = mysqli_prepare($connect, $sqlOrder);
        mysqli_stmt_bind_param($stmtOrder, "i", $orderId);
        mysqli_stmt_execute($stmtOrder);

        // 4. Update Delivery Person Status to 'available'
        $sqlDelivery = "UPDATE Delivery_person SET status = 'available' WHERE del_id = ?";
        $stmtDel = mysqli_prepare($connect, $sqlDelivery);
        mysqli_stmt_bind_param($stmtDel, "i", $deliveryId);
        mysqli_stmt_execute($stmtDel);

        // Commit all changes to the database
        mysqli_commit($connect);
        
    } else {
        throw new Exception("Order not found or not assigned to you.");
    }

} catch (Exception $e) {
    // Rollback if any query fails
    mysqli_rollback($connect);
    die("Error processing delivery: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Complete</title>
    <style>
        body { 
            font-family: 'Segoe UI', Arial; 
            background: #2c3e50; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            margin: 0; 
        }
        .msg-box { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3); 
            width: 350px; 
        }
        h1 { color: #27ae60; margin: 0; }
        p { color: #333; font-size: 1.1rem; }
    </style>
</head>
<body>
    <div class="msg-box">
        <h1>✔ Success!</h1>
        <p>Order marked as delivered.<br>Sales record created.</p>
        <p><small>Redirecting...</small></p>
    </div>
    <script>
        setTimeout(function() {
            window.location.href = 'assigned_order.php';
        }, 2000);
    </script>
</body>
</html>
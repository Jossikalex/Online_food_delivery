<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();

require_once "../connection.php";

/* 🔐 SECURITY CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'delivery') {
    header("Location: ../login.php");
    exit();
}

if (!$connect) { exit(); }

$deliveryId = (int)$_SESSION['user_id'];

/* 📦 FETCH ASSIGNED ORDERS */
$sql = "
    SELECT 
        o.order_id,
        o.status,
        c.Fname,
        c.Lname,
        o.delivery_address
    FROM `Order` o
    JOIN Customer c ON o.cust_id = c.cust_id
    WHERE o.del_id = ? 
    AND (o.status = 'ready' OR o.status = 'out_for_delivery')
";

$stmt = mysqli_prepare($connect, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $deliveryId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assigned Orders</title>
    <style>
        /* --- INTERNAL CSS --- */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .content {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }
        tr:hover {
            background-color: #f9f9f9;
        }

        /* Button Styling */
        .btn {
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
        }
        .btn-accept {
            background-color: #27ae60;
            color: white;
            margin-right: 5px;
        }
        .btn-accept:hover { background-color: #219150; }

        .btn-reject {
            background-color: #e74c3c;
            color: white;
        }
        .btn-reject:hover { background-color: #c0392b; }

        .btn-logout {
            background-color: #95a5a6;
            color: white;
            margin-top: 20px;
        }
        .btn-logout:hover { background-color: #7f8c8d; }

        .status-badge {
            background: #e1f5fe;
            color: #01579b;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <header class="header">Ella Kitchen Delivery Dashboard</header>

    <main class="content">
        <h2>Your Assigned Orders</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$result || mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#777;">
                            No active orders assigned to you at the moment.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><strong>#<?= (int)$row['order_id'] ?></strong></td>
                        <td><?= htmlspecialchars($row['Fname'] . ' ' . $row['Lname']) ?></td>
                        <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                        <td><span class="status-badge"><?= strtoupper(htmlspecialchars($row['status'])) ?></span></td>
                        <td style="text-align:center;">
                            <button class="btn btn-accept" 
                                onclick="window.location.href='accept_order.php?id=<?= (int)$row['order_id'] ?>'">
                                Accept
                            </button>
                            
                            <button class="btn btn-reject" 
                                onclick="if(confirm('Are you sure you want to reject this order?')) window.location.href='reject_order.php?id=<?= (int)$row['order_id'] ?>'">
                                Reject
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <button class="btn btn-logout" onclick="window.location.href='../logout.php'">
            Logout
        </button>
    </main>

</body>
</html>
<?php
// Hide errors for a clean interface
error_reporting(0);
ini_set('display_errors', 0);

session_start();

// Path to connection.php from the delivery_boy folder
require_once "../connection.php"; 

/* ===============================
    SECURITY: LOGIN + ROLE CHECK
   =============================== */
if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'delivery'
) {
    header("Location: ../login.php");
    exit();
}

// Use the correct connection variable $connect
if (!$connect) {
    exit();
}

$deliveryId = (int) $_SESSION['user_id'];

/* ===============================
    FETCH CURRENT DELIVERY + COORDINATES
   =============================== */
$sql = "
    SELECT 
        o.order_id,
        c.Fname,
        c.Lname,
        c.phone,
        o.delivery_address,
        o.status,
        ST_Y(c.location) AS lat,
        ST_X(c.location) AS lon
    FROM `Order` o
    JOIN Customer c ON o.cust_id = c.cust_id
    WHERE o.del_id = ?
      AND o.status = 'out_for_delivery'
";

$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "i", $deliveryId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Current Delivery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* --- INTERNAL CSS (Matches Assigned Orders) --- */
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
            max-width: 1100px;
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
        .card {
            overflow-x: auto;
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
            text-decoration: none;
            display: inline-block;
        }
        .btn-map {
            background-color: #4285F4;
            color: white;
            margin-right: 5px;
        }
        .btn-map:hover { background-color: #357ae8; }

        .btn-delivered {
            background-color: #27ae60;
            color: white;
        }
        .btn-delivered:hover { background-color: #219150; }

        .btn-back {
            background-color: #95a5a6;
            color: white;
            margin-top: 20px;
        }
        .btn-back:hover { background-color: #7f8c8d; }

        .status-badge {
            background: #fff3e0;
            color: #ef6c00;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .empty {
            text-align: center;
            padding: 30px;
            color: #777;
        }
    </style>
</head>
<body>

<header class="header">Ella Kitchen Delivery Dashboard</header>

<main class="content">
    <h2>Current Active Delivery</h2>
    
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) === 0): ?>
                    <tr>
                        <td colspan="6" class="empty">No current delivery in progress.</td>
                    </tr>
                <?php else: ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        // Google Maps URL format using Latitude and Longitude
                        $googleMapsUrl = "https://www.google.com/maps/search/?api=1&query=" . $row['lat'] . "," . $row['lon'];
                    ?>
                    <tr>
                        <td><strong>#<?= (int)$row['order_id'] ?></strong></td>
                        <td><?= htmlspecialchars($row['Fname'] . ' ' . $row['Lname']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                        <td><span class="status-badge"><?= strtoupper(htmlspecialchars($row['status'])) ?></span></td>
                        <td style="text-align:center; min-width: 250px;">
                            <?php if (!empty($row['lat']) && !empty($row['lon'])): ?>
                                <button type="button" class="btn btn-map" 
                                        onclick="window.open('<?= $googleMapsUrl ?>', '_blank')">
                                    📍 View Map
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn" style="background:#eee; color:#aaa; cursor:not-allowed;" disabled>
                                    No GPS
                                </button>
                            <?php endif; ?>

                     <button type="button" class="btn btn-delivered" 
        onclick="if(confirm('Mark as delivered?')) window.location.href='mark_delivered.php?id=<?= (int)$row['order_id'] ?>'">
    Delivered
</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <button type="button" class="btn btn-back" onclick="window.location.href='assigned_order.php'">
        ← Back to Assigned Orders
    </button>
</main>

</body>
</html>
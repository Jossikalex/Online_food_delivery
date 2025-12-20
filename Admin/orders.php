<?php
require_once 'connection.php';

$statusMap = [
    'pending'          => 'Pending',
    'confirmed'        => 'Confirmed',
    'preparing'        => 'Preparing',
    'ready'            => 'Ready',
    'out_for_delivery' => 'Out',
    'delivered'        => 'Delivered',
    'cancelled'        => 'Cancelled'
];

$ordersRes = mysqli_query(
    $connect,
    "SELECT o.order_id, o.order_date, o.total_price, o.status,
            CONCAT(c.Fname,' ',c.Lname) customer, c.email
     FROM `Order` o 
     JOIN Customer c ON o.cust_id = c.cust_id 
     ORDER BY o.order_id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Orders</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-dashboard">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1>Manage Orders</h1>
            <div class="profile-box">
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
                <p class="username"><?= htmlspecialchars($_SESSION['adminUsername'] ?? 'Admin') ?></p>
            </div>
        </header>

        <div class="admin-content">
            <div class="content-section">
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($ordersRes)): ?>
                                <tr data-status="<?= $row['status'] ?>">
                                    <td>#<?= $row['order_id'] ?></td>
                                    <td>
                                        <strong><?= htmlspecialchars($row['customer']) ?></strong><br>
                                        <small><?= htmlspecialchars($row['email']) ?></small>
                                    </td>
                                    <td>
                                        <?= date('Y-m-d', strtotime($row['order_date'])) ?><br>
                                        <small><?= date('H:i', strtotime($row['order_date'])) ?></small>
                                    </td>
                                    <td><?= number_format($row['total_price']) ?> Birr</td>
                                    <td>
                                        <form method="POST" action="saveOrderStatus.php" style="display:inline">
                                            <input type="hidden" name="order_id" value="<?= $row['order_id'] ?>">
                                            <select name="status" class="status-select" onchange="this.form.submit()">
                                                <?php foreach ($statusMap as $k => $v): ?>
                                                    <option value="<?= $k ?>" <?= $k == $row['status'] ? 'selected' : '' ?>>
                                                        <?= $v ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="orderDetail.php?id=<?= $row['order_id'] ?>" 
                                           class="btn btn-small btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="Javascript/admin.js"></script>
</body>
</html>

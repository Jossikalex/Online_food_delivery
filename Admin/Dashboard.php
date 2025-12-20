<?php
require_once 'connection.php';
session_start();

$orders  = (int)(mysqli_fetch_assoc(
    mysqli_query($connect, "SELECT COUNT(*) c FROM `Order`")
)['c'] ?? 0);

$revenue = (int)(mysqli_fetch_assoc(
    mysqli_query($connect, "SELECT COALESCE(SUM(final_price),0) s FROM `Order` WHERE status='delivered'")
)['s'] ?? 0);

$foods   = (int)(mysqli_fetch_assoc(
    mysqli_query($connect, "SELECT COUNT(*) c FROM Foods WHERE active='yes'")
)['c'] ?? 0);

$cats    = (int)(mysqli_fetch_assoc(
    mysqli_query($connect, "SELECT COUNT(*) c FROM Categories WHERE active='yes'")
)['c'] ?? 0);

$rec = mysqli_query(
    $connect,
    "SELECT o.order_id, o.order_date, o.total_price, o.status,
            CONCAT(c.Fname,' ',c.Lname) customer
     FROM `Order` o 
     JOIN Customer c ON o.cust_id = c.cust_id
     ORDER BY o.order_id DESC 
     LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Ella Kitchen Cafe</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-dashboard">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1>Dashboard Overview</h1>
            <div class="profile-box">
                <div class="avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <p class="username">
                    <?= htmlspecialchars($_SESSION['adminUsername'] ?? 'Admin') ?>
                </p>
            </div>
        </header>

        <div class="admin-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <p class="stat-number"><?= $orders ?></p>
                    <span class="stat-trend"><i class="fas fa-arrow-up"></i> live</span>
                </div>

                <div class="stat-card">
                    <h3>Revenue (delivered)</h3>
                    <p class="stat-number"><?= number_format($revenue) ?> Birr</p>
                    <span class="stat-trend"><i class="fas fa-arrow-up"></i> this month</span>
                </div>

                <div class="stat-card">
                    <h3>Menu Items</h3>
                    <p class="stat-number"><?= $foods ?></p>
                    <span class="stat-trend"><i class="fas fa-minus"></i> active</span>
                </div>

                <div class="stat-card">
                    <h3>Categories</h3>
                    <p class="stat-number"><?= $cats ?></p>
                    <span class="stat-trend"><i class="fas fa-arrow-up"></i> active</span>
                </div>
            </div>

            <div class="content-section">
                <h2>Recent Orders</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($r = mysqli_fetch_assoc($rec)): ?>
                                <tr>
                                    <td>#<?= $r['order_id'] ?></td>
                                    <td><?= htmlspecialchars($r['customer']) ?></td>
                                    <td><?= date('Y-m-d', strtotime($r['order_date'])) ?></td>
                                    <td><?= number_format($r['total_price']) ?> Birr</td>
                                    <td>
                                        <span class="status <?= $r['status'] ?>">
                                            <?= ucfirst($r['status']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <p style="text-align:center; margin-top:15px">
                    <a href="orders.php" class="btn btn-primary">
                        <i class="fas fa-list"></i> View All Orders
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script src="Javascript/admin.js"></script>
</body>
</html>

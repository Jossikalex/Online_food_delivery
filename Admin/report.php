<?php
require_once 'connection.php';

$period = $_GET['period'] ?? 'this_month';

switch ($period) {
    case 'today':
        $dStart = date('Y-m-d');
        $dEnd   = $dStart;
        break;
    case 'yesterday':
        $dStart = date('Y-m-d', strtotime('-1 day'));
        $dEnd   = $dStart;
        break;
    case 'this_week':
        $dStart = date('Y-m-d', strtotime('monday this week'));
        $dEnd   = date('Y-m-d');
        break;
    case 'last_week':
        $dStart = date('Y-m-d', strtotime('monday last week'));
        $dEnd   = date('Y-m-d', strtotime('sunday last week'));
        break;
    case 'last_month':
        $dStart = date('Y-m-01', strtotime('-1 month'));
        $dEnd   = date('Y-m-t', strtotime('-1 month'));
        break;
    default:
        $dStart = date('Y-m-01');
        $dEnd   = date('Y-m-d');
}

$stats = mysqli_fetch_assoc(
    mysqli_query(
        $connect,
        "SELECT COUNT(*) orders, COALESCE(SUM(final_price),0) revenue 
         FROM `Order` 
         WHERE status='delivered' 
         AND DATE(order_date) BETWEEN '$dStart' AND '$dEnd'"
    )
);

$topItems = mysqli_query(
    $connect,
    "SELECT f.name, c.name category, 
            SUM(oi.quantity) sold, 
            SUM(oi.quantity*oi.price) revenue 
     FROM Order_item oi 
     JOIN Foods f ON oi.food_id = f.food_id 
     JOIN Categories c ON f.category_id = c.category_id 
     JOIN `Order` o ON oi.order_id = o.order_id 
     WHERE o.status='delivered' 
       AND DATE(o.order_date) BETWEEN '$dStart' AND '$dEnd' 
     GROUP BY oi.food_id 
     ORDER BY sold DESC 
     LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" 
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-dashboard">
    <?php include 'sidebar.php'; ?>

    <div class="admin-main">
        <header class="admin-header">
            <h1>Business Reports</h1>
            <div class="profile-box">
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
                <p class="username"><?= htmlspecialchars($_SESSION['adminUsername'] ?? 'Admin') ?></p>
            </div>
        </header>

        <div class="admin-content">
            <!-- Report Period Selector -->
            <div class="content-section">
                <form method="GET" class="form-row">
                    <div class="form-group">
                        <label>Report Period</label>
                        <select name="period" onchange="this.form.submit()">
                            <?php foreach (['today','yesterday','this_week','last_week','this_month','last_month'] as $p): ?>
                                <option value="<?= $p ?>" <?= $period == $p ? 'selected' : '' ?>>
                                    <?= ucfirst(str_replace('_',' ',$p)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Orders</h3>
                    <p class="stat-number"><?= $stats['orders'] ?></p>
                </div>
                <div class="stat-card">
                    <h3>Revenue</h3>
                    <p class="stat-number"><?= number_format($stats['revenue']) ?> Birr</p>
                </div>
            </div>

            <!-- Top Performing Items -->
            <div class="content-section">
                <h2><i class="fas fa-star"></i> Top Performing Items</h2>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Qty Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $rank = 1; while ($t = mysqli_fetch_assoc($topItems)): ?>
                                <tr>
                                    <td><?= $rank++ ?></td>
                                    <td><?= htmlspecialchars($t['name']) ?></td>
                                    <td><?= htmlspecialchars($t['category']) ?></td>
                                    <td><?= $t['sold'] ?></td>
                                    <td><?= number_format($t['revenue']) ?> Birr</td>
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

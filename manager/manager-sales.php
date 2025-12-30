<?php
session_start();
if (!($_SESSION['manager_logged_in'] ?? false)) {
    header('Location: ../login.php');
    exit;
}
require_once '../connection.php';

/* =====  AJAX  ===== */
if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];
    switch ($action) {
        case 'summary':
    $today = $connect->query("
        SELECT COALESCE(SUM(s.total_amount), 0)
        FROM Sales s
        JOIN `Order` o ON o.order_id = s.order_id
        WHERE DATE(o.order_date) = CURDATE()
    ")->fetch_row()[0];

    $week  = $connect->query("
        SELECT COALESCE(SUM(s.total_amount), 0)
        FROM Sales s
        JOIN `Order` o ON o.order_id = s.order_id
        WHERE YEARWEEK(o.order_date, 1) = YEARWEEK(CURDATE(), 1)
    ")->fetch_row()[0];

    $month = $connect->query("
        SELECT COALESCE(SUM(s.total_amount), 0)
        FROM Sales s
        JOIN `Order` o ON o.order_id = s.order_id
        WHERE YEAR(o.order_date) = YEAR(CURDATE()) AND MONTH(o.order_date) = MONTH(CURDATE())
    ")->fetch_row()[0];

    echo json_encode(['today'=>(float)$today, 'week'=>(float)$week, 'month'=>(float)$month]);
    break;

       case 'categories':
    $res = $connect->query("
        SELECT c.name  AS category,
               COUNT(*) AS orders,
               SUM(s.total_amount) AS revenue
        FROM   Sales s
        JOIN   `Order`   o ON o.order_id = s.order_id
        JOIN   Order_item i ON i.order_id = o.order_id
        JOIN   Foods  f ON f.food_id  = i.food_id
        JOIN   Categories c ON c.category_id = f.category_id
        WHERE  o.status = 'delivered'
        GROUP  BY c.category_id
        ORDER  BY revenue DESC
    ");
    $rows = []; $total = 0;
    while ($r = $res->fetch_assoc()) { $rows[] = $r; $total += $r['revenue']; }
    array_walk($rows, fn(&$r,$_)=> [
        $r['percentage'] = $total>0 ? round($r['revenue']/$total*100) : 0,
        $r['revenue']    = (float)$r['revenue'],
        $r['orders']     = (int)$r['orders']
    ]);
    echo json_encode($rows);
    break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Sales Report – Ella Kitchen</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/manager.css">
</head>
<body>
  <div class="manager-container">
    <aside class="sidebar" id="sidebar">
      <div class="sidebar-header"
           style="background:url('images/logo.png') center/contain no-repeat; opacity:0.2; height:150px;
                  display:flex; align-items:center; justify-content:center">
        <h3 style="margin:0; color:#333; position:relative; z-index:1">Manager Panel</h3>
      </div>
      <ul class="sidebar-menu">
        <li><a href="manager-dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li><a href="manager-application.php"><i class="fas fa-file-alt"></i><span>Application</span></a></li>
        <li><a href="manager-complaints.php"><i class="fas fa-exclamation-circle"></i><span>Complaints</span></a></li>
        <li class="active"><a href="manager-sales.php"><i class="fas fa-chart-line"></i><span>Sales Report</span></a></li>
       
      </ul>
      <div class="sidebar-footer"><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></div>
    </aside>

    <div class="main-content">
      <div class="top-bar" style="display:flex; align-items:center; justify-content:center">
        <button class="menu-toggle" id="menuToggle" style="position:absolute; left:1rem"><i class="fas fa-bars"></i></button>
        <h1>WELCOME ELLA KITCHEN CAFE MANAGER</h1>
      </div>
      <div class="content-area">
        <div class="page-header" style="display:flex; align-items:center; justify-content:space-between">
          <h2>Sales Report</h2>
          <span class="date-range-label" style="font-size:0.9rem; color:#666"><?= date('M d, Y') ?></span>
        </div>
        <div class="sales-summary" style="display:flex; gap:1rem; margin-bottom:1.5rem">
          <div class="summary-card" style="flex:1"><h3>Today’s Sales</h3><p class="amount">-</p></div>
          <div class="summary-card" style="flex:1"><h3>This Week</h3><p class="amount">-</p></div>
          <div class="summary-card" style="flex:1"><h3>This Month</h3><p class="amount">-</p></div>
        </div>
        <div class="chart-card">
          <h3>Sales by Category</h3>
          <table class="data-table"><thead><tr><th>Category</th><th>Orders</th><th>Revenue</th><th>Percentage</th></tr></thead><tbody></tbody></table>
        </div>
      </div>
    </div>
  </div>
  <script src="js/manager.js"></script>
</body>
</html>
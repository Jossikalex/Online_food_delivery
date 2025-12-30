<?php
         // keeps non-managers out
require_once '../connection.php'; // your existing path
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Application – Ella Kitchen</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="css/manager.css">
</head>
<body>
  <div class="manager-container">
    <aside class="sidebar" id="sidebar">
     <div class="sidebar-header"
     style="background:url('images/logo.png') center/contain no-repeat;
            opacity:0.2;
            height:150px;
            display:flex;
            align-items:center;
            justify-content:center">
     <h3 style="margin:0; color:#333; position:relative; z-index:1">Manager Panel</h3>
</div>
      <ul class="sidebar-menu">
        <li><a href="manager-dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
        <li class="active"><a href="manager_application.php"><i class="fas fa-file-alt"></i><span>Application</span></a></li>
        <li><a href="manager-complaints.php"><i class="fas fa-exclamation-circle"></i><span>Complaints</span></a></li>
        <li><a href="manager-sales.php"><i class="fas fa-chart-line"></i><span>Sales Report</span></a></li>
     
      </ul>
      <div class="sidebar-footer"><a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></div>
    </aside>

    <div class="main-content">
    <div class="top-bar" style="display:flex; align-items:center; justify-content:center">
  <button class="menu-toggle" id="menuToggle" style="position:absolute; left:1rem"><i class="fas fa-bars"></i></button>
  <h1>WELCOME ELLA KITCHEN CAFE MANAGER</h1>
</div>
 <div class="content-area">
      <div style="display:flex; justify-content:center">
  <h2>Apply for Admin</h2>
</div>

      <div class="content-area">

        <div class="form-card">
          <form id="applicationForm">
            <div class="form-group"><label>Full Name</label><input type="text" required></div>
            <div class="form-group"><label>Email</label><input type="email" required></div>
            <div class="form-group"><label>Phone Number</label><input type="tel" required></div>
            <div class="form-group"><label>Years of Experience</label><input type="number" required></div>
            <div class="form-group"><label>Message</label><textarea rows="4"></textarea></div>
            <button class="btn-primary">Submit Application</button>
          </form>
        </div>
      </div>
    </div>
  </div>
<script src="js/manager.js"></script>
</body>
</html>
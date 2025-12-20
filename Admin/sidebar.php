<?php
session_start();
$cur = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar">
    <div class="sidebar-header">
        <img src="images/logo.png" alt="Logo">
        <h3>Ella Kitchen Cafe</h3>
        <p>Admin Panel</p>
    </div>

    <nav class="sidebar-menu">
        <ul>
            <li class="<?= $cur == 'Dashboard.php' ? 'active' : '' ?>">
                <a href="Dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="<?= $cur == 'menu.php' ? 'active' : '' ?>">
                <a href="menu.php"><i class="fas fa-utensils"></i> Menu</a>
            </li>
            <li class="<?= $cur == 'orders.php' ? 'active' : '' ?>">
                <a href="orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
            <li class="<?= $cur == 'customers.php' ? 'active' : '' ?>">
                <a href="customers.php"><i class="fas fa-users"></i> Customers</a>
            </li>
            <li class="<?= $cur == 'Registration.php' ? 'active' : '' ?>">
                <a href="Registration.php"><i class="fas fa-user-plus"></i> Staff Reg.</a>
            </li>
            <li class="<?= $cur == 'report.php' ? 'active' : '' ?>">
                <a href="report.php"><i class="fas fa-chart-bar"></i> Reports</a>
            </li>
            <li>
                <a href="#" class="logout-btn" onclick="adminUtils.confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
</div>

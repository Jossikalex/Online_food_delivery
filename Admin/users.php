<?php
/**
 * USERS MANAGEMENT PAGE
 * Ella Kitchen Cafe Admin Panel
 * Manages staff members, waiters, and delivery personnel
 */

require_once 'connection.php';

// ============================================================================
// 1. PAGINATION & FILTERING SETUP
// ============================================================================
$records_per_page = 5;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $records_per_page;

// Search and filter variables
$search = isset($_GET['search']) ? mysqli_real_escape_string($connect, $_GET['search']) : '';
$role_filter = isset($_GET['role_filter']) ? mysqli_real_escape_string($connect, $_GET['role_filter']) : 'all';

// Build WHERE conditions
$where_conditions = [];
if (!empty($search)) {
    $where_conditions[] = "(fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%' OR phone LIKE '%$search%')";
}
if ($role_filter !== 'all') {
    $where_conditions[] = "role = '$role_filter'";
}

$where_sql = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// ============================================================================
// 2. GET TOTAL RECORDS COUNT
// ============================================================================
$count_query = "SELECT COUNT(*) as total FROM users $where_sql";
$count_result = mysqli_query($connect, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];
$total_pages = ceil($total_records / $records_per_page);

// Ensure current page is within bounds
if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $records_per_page;
}

// ============================================================================
// 3. FETCH USERS WITH PAGINATION
// ============================================================================
$query = "SELECT user_id, 
                 CONCAT(fname, ' ', lname) as full_name, 
                 email, 
                 phone, 
                 role, 
                 created_at 
          FROM users 
          $where_sql 
          ORDER BY user_id DESC 
          LIMIT $records_per_page OFFSET $offset";

$result = mysqli_query($connect, $query);

// Check for query error
if (!$result) {
    die("Database error: " . mysqli_error($connect));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Ella Kitchen Cafe Admin</title>
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="admin-dashboard">
    <!-- Sidebar Navigation -->
    <div class="admin-sidebar">
        <div class="sidebar-header">
            <img src="images/logo.png" alt="Ella Kitchen Cafe Logo">
            <h3>Ella Kitchen Cafe</h3>
            <p>Admin Panel</p>
        </div>
        
        <nav class="sidebar-menu">
            <ul>
                <li><a href="Dashboard.html" class="nav-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard</a></li>

                <li><a href="update-menu.php" class="nav-item">
                    <i class="fas fa-utensils"></i> Update Menu</a></li>    

                <li><a href="orders.html" class="nav-item">
                    <i class="fas fa-shopping-cart"></i> Orders</a></li>

                <li class="active"><a href="users.php" class="nav-item">
                    <i class="fas fa-users"></i> Users</a></li>

                <li><a href="Registration.php" class="nav-item">
                    <i class="fas fa-user-plus"></i> Registration</a></li>

                <li><a href="report.html" class="nav-item">
                    <i class="fas fa-chart-bar"></i> Reports</a></li>  

                <li><a href="#" class="nav-item" onclick="alert('View site feature coming soon')">
                    <i class="fas fa-external-link-alt"></i> View Site</a></li>  
                    
                <li><a href="#" class="nav-item logout-btn" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout</a></li>  
            </ul>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <header class="admin-header">
            <h1>Manage Users</h1>
            <div class="admin-header-right">
                <div class="profile-box">
                    <div class="avatar"><i class="fa-solid fa-user"></i></div>
                    <p class="username">Jossikalex</p>
                </div>
            </div>
        </header>
        
        <div class="admin-content">
            <!-- Search and Filter Form -->
            <div class="search-section">
                <form method="GET" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Search Users</label>
                            <input type="text" name="search" placeholder="Search by name, email or phone..." 
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="form-group">
                            <label>Filter by Role</label>
                            <select name="role_filter">
                                <option value="all" <?php echo $role_filter === 'all' ? 'selected' : ''; ?>>All Users</option>
                                <option value="waiter" <?php echo $role_filter === 'waiter' ? 'selected' : ''; ?>>Waiters</option>
                                <option value="delivery" <?php echo $role_filter === 'delivery' ? 'selected' : ''; ?>>Delivery Persons</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" style="height: 42px;">
                                <i class="fas fa-search"></i> Search
                            </button>
                            <?php if (!empty($search) || $role_filter !== 'all'): ?>
                                <a href="users.php" class="btn btn-secondary" style="height: 42px; margin-left: 10px;">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Users Table -->
            <div class="content-section">
                <h2>All Users 
                    <small style="color: #666; font-size: 14px;">
                        (Showing <?php echo ($total_records > 0) ? ($offset + 1) : 0; ?>-<?php 
                        echo min($offset + $records_per_page, $total_records); ?> 
                        of <?php echo $total_records; ?> users)
                    </small>
                </h2>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Joined Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td>#USR-<?php echo str_pad($row['user_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['full_name']); ?></strong><br>
                                            <small style="color: #666;">
                                                <?php echo ucfirst($row['role']); ?>
                                            </small>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                        <td>
                                            <span class="role-badge <?php echo $row['role']; ?>">
                                                <?php echo ucfirst($row['role']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('Y-m-d', strtotime($row['created_at'])); ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-small" 
                                                    onclick="adminUtils.viewUser(<?php echo $row['user_id']; ?>)">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7">
                                        <div class="no-data">
                                            <i class="fas fa-user-slash"></i>
                                            <h3>No users found</h3>
                                            <p>
                                                <?php if (!empty($search) || $role_filter !== 'all'): ?>
                                                    Try changing your search criteria or 
                                                    <a href="users.php">view all users</a>
                                                <?php else: ?>
                                                    No users registered yet. 
                                                    <a href="Registration.php">Register a new user</a>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <!-- Previous button -->
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?php echo $current_page - 1; ?>&search=<?php 
                                echo urlencode($search); ?>&role_filter=<?php echo $role_filter; ?>"
                               class="btn btn-small btn-secondary">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        <?php else: ?>
                            <button class="btn btn-small btn-secondary" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                        <?php endif; ?>
                        
                        <!-- Page indicator -->
                        <span style="margin: 0 10px;">
                            Page <?php echo $current_page; ?> of <?php echo $total_pages; ?>
                        </span>
                        
                        <!-- Next button -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?php echo $current_page + 1; ?>&search=<?php 
                                echo urlencode($search); ?>&role_filter=<?php echo $role_filter; ?>"
                               class="btn btn-small btn-secondary">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-small btn-secondary" disabled>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="Javascript/admin.js"></script>
    
    <script>
    // Initialize page-specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add click handlers to all "View" buttons
        document.querySelectorAll('.btn-small').forEach(button => {
            if (button.textContent.includes('View')) {
                button.addEventListener('click', function() {
                    const userId = this.closest('tr').querySelector('td:first-child').textContent;
                    adminUtils.showToast(`Opening user details for ${userId}`, 'info');
                });
            }
        });
        
        // Auto-refresh user list every 60 seconds
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                const currentParams = new URLSearchParams(window.location.search);
                if (!currentParams.has('search') && currentParams.get('page') === '1') {
                    // In production: Implement AJAX refresh
                    console.log('Auto-refresh user list');
                }
            }
        }, 60000);
    });
    </script>
</body>
</html>
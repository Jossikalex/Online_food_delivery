<?php
/**
 * MENU MANAGEMENT PAGE
 * Ella Kitchen Cafe Admin Panel
 * Add, edit, delete, and toggle menu items
 */

session_start();
require_once 'connection.php';

$success = null;
$error = null;

// ============================================================================
// 1. HANDLE FORM SUBMISSIONS
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    
    if ($action === 'add') {
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $description = mysqli_real_escape_string($connect, $_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = mysqli_real_escape_string($connect, $_POST['category']);
        $image_path = mysqli_real_escape_string($connect, $_POST['image_path'] ?? '');
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        // Validate required fields
        if (empty($name) || empty($price) || empty($category)) {
            $_SESSION['error_message'] = "Please fill in all required fields!";
        } else {
            $sql = "INSERT INTO menu_items (name, description, price, category, image_path, is_available) 
                    VALUES ('$name', '$description', $price, '$category', '$image_path', $is_available)";
            
            if (mysqli_query($connect, $sql)) {
                $_SESSION['success_message'] = "Menu item added successfully!";
            } else {
                $_SESSION['error_message'] = "Error: " . mysqli_error($connect);
            }
        }
    } 
    elseif ($action === 'update') {
        $name = mysqli_real_escape_string($connect, $_POST['name']);
        $description = mysqli_real_escape_string($connect, $_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category = mysqli_real_escape_string($connect, $_POST['category']);
        $image_path = mysqli_real_escape_string($connect, $_POST['image_path'] ?? '');
        
        $sql = "UPDATE menu_items SET 
                name = '$name', 
                description = '$description', 
                price = $price, 
                category = '$category', 
                image_path = '$image_path' 
                WHERE id = $id";
        
        if (mysqli_query($connect, $sql)) {
            $_SESSION['success_message'] = "Menu item updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($connect);
        }
    }
    elseif ($action === 'delete') {
        $sql = "DELETE FROM menu_items WHERE id = $id";
        if (mysqli_query($connect, $sql)) {
            $_SESSION['success_message'] = "Menu item deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($connect);
        }
    }
    elseif ($action === 'toggle_availability') {
        $is_available = intval($_POST['is_available']);
        $sql = "UPDATE menu_items SET is_available = $is_available WHERE id = $id";
        if (mysqli_query($connect, $sql)) {
            $_SESSION['success_message'] = "Availability updated successfully!";
        } else {
            $_SESSION['error_message'] = "Error: " . mysqli_error($connect);
        }
    }
    
    // PRG (Post-Redirect-Get) pattern to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// ============================================================================
// 2. FETCH MENU ITEMS
// ============================================================================
$menu_items = [];
$result = mysqli_query($connect, "SELECT * FROM menu_items ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menu_items[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu - Ella Kitchen Cafe Admin</title>
    
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

                <li class="active"><a href="update-menu.php" class="nav-item">
                    <i class="fas fa-utensils"></i> Update Menu</a></li>    

                <li><a href="orders.html" class="nav-item">
                    <i class="fas fa-shopping-cart"></i> Orders</a></li>

                <li><a href="users.php" class="nav-item">
                    <i class="fas fa-users"></i> Users</a></li>

                <li><a href="Registration.php" class="nav-item">
                    <i class="fas fa-user-plus"></i> Registration</a></li>

                <li><a href="report.html" class="nav-item">
                    <i class="fas fa-chart-bar"></i> Reports</a></li>  

                <li><a href="#" class="nav-item" onclick="alert('View site feature coming soon')">
                    <i class="fas fa-external-link-alt"></i> View Site</a></li>  
                    
                <li><a href="#" class="nav-item logout-btn" onclick="adminUtils.confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout</a></li>  
            </ul>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="admin-main">
        <header class="admin-header">
            <h1>Manage Menu</h1>
            <div class="admin-header-right">
                <div class="profile-box">
                    <div class="avatar"><i class="fa-solid fa-user"></i></div>
                    <p class="username">Jossikalex</p>
                </div>
            </div>
        </header>
        
        <div class="admin-content">
            <!-- Display Messages -->
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            
            <!-- Add New Menu Item Form -->
            <div class="content-section">
                <h2><i class="fas fa-plus-circle"></i> Add New Menu Item</h2>
                <form class="form-container" method="POST" action="" id="addForm">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Item Name *</label>
                            <input type="text" id="name" name="name" 
                                   placeholder="e.g., Margherita Pizza, Classic Burger" required>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (Birr) *</label>
                            <input type="number" id="price" name="price" 
                                   step="0.01" min="0" placeholder="0.00" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Category *</label>
                            <input type="text" id="category" name="category" 
                                   placeholder="e.g., Pizza, Burger, Desserts" required>
                        </div>
                        <div class="form-group">
                            <label for="image_path">Image URL</label>
                            <input type="text" id="image_path" name="image_path" 
                                   placeholder="images/menu-item.jpg"
                                   oninput="adminUtils.updateImagePreview(this.value, 'image-preview')">
                            <div class="image-preview-container">
                                <img id="image-preview" class="image-preview" style="display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" 
                                  placeholder="Enter item description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_available" checked>
                            <span>Available for order</span>
                        </label>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Item
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-eraser"></i> Clear
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Menu Items List -->
            <div class="content-section">
                <h2><i class="fas fa-list"></i> Existing Menu Items (<?php echo count($menu_items); ?>)</h2>
                
                <?php if (empty($menu_items)): ?>
                    <div class="no-data">
                        <i class="fas fa-utensils"></i>
                        <h3>No menu items found</h3>
                        <p>Add your first menu item using the form above!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menu_items as $item): ?>
                                    <tr>
                                        <td>#<?php echo $item['id']; ?></td>
                                        <td>
                                            <?php if (!empty($item['image_path'])): ?>
                                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                     class="table-img-small"
                                                     onerror="this.src='images/default-food.jpg'">
                                            <?php else: ?>
                                                <img src="images/default-food.jpg" 
                                                     alt="No image" 
                                                     class="table-img-small">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['description']); ?></td>
                                        <td><?php echo number_format($item['price'], 2); ?> Birr</td>
                                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                                        <td>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="action" value="toggle_availability">
                                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                <input type="hidden" name="is_available" 
                                                       value="<?php echo $item['is_available'] ? 0 : 1; ?>">
                                                <button type="submit" class="status-toggle <?php 
                                                    echo $item['is_available'] ? 'available' : 'unavailable'; ?>">
                                                    <?php echo $item['is_available'] ? 'Available' : 'Unavailable'; ?>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button type="button" class="btn btn-small btn-primary edit-btn"
                                                        data-id="<?php echo $item['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($item['name']); ?>"
                                                        data-description="<?php echo htmlspecialchars($item['description']); ?>"
                                                        data-price="<?php echo $item['price']; ?>"
                                                        data-category="<?php echo htmlspecialchars($item['category']); ?>"
                                                        data-image="<?php echo htmlspecialchars($item['image_path'] ?? ''); ?>"
                                                        onclick="adminUtils.editMenuItem(this)">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form method="POST" action="" style="display: inline;" 
                                                      onsubmit="return confirm('Delete <?php echo htmlspecialchars(addslashes($item['name'])); ?>?')">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                    <button type="submit" class="btn btn-small btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <h2><i class="fas fa-edit"></i> Edit Menu Item</h2>
            <form id="editForm" method="POST" action="">
                <input type="hidden" name="action" value="update">
                <input type="hidden" id="edit_id" name="id">
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Name *</label>
                        <input type="text" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label>Price (Birr) *</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Category *</label>
                        <input type="text" id="edit_category" name="category" required>
                    </div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" id="edit_image_path" name="image_path"
                               oninput="adminUtils.updateImagePreview(this.value, 'edit_image_preview')">
                        <div class="image-preview-container">
                            <img id="edit_image_preview" class="image-preview" style="display: none;">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="edit_description" name="description" rows="3"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Item
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="adminUtils.closeEditModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="Javascript/admin.js"></script>
    
    <script>
    // Menu page specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Form validation
        const addForm = document.getElementById('addForm');
        const editForm = document.getElementById('editForm');
        
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const required = this.querySelectorAll('[required]');
                let valid = true;
                
                required.forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                        input.style.borderColor = '#dc3545';
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    adminUtils.showToast('Please fill in all required fields', 'error');
                }
            });
        }
        
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const required = this.querySelectorAll('[required]');
                let valid = true;
                
                required.forEach(input => {
                    if (!input.value.trim()) {
                        valid = false;
                        input.style.borderColor = '#dc3545';
                    }
                });
                
                if (!valid) {
                    e.preventDefault();
                    adminUtils.showToast('Please fill in all required fields', 'error');
                }
            });
        }
        
        // Status toggle styling
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.classList.add(toggle.textContent.toLowerCase());
        });
    });
    </script>
</body>
</html>
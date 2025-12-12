<?php
/**
 * STAFF REGISTRATION PAGE
 * Ella Kitchen Cafe Admin Panel
 * Register waiters and delivery personnel
 */

require_once 'connection.php';

// Initialize variables
$success = null;
$error = null;
$formData = [];

// ============================================================================
// 1. PROCESS FORM SUBMISSION
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize form data
    $fname = mysqli_real_escape_string($connect, $_POST['fname'] ?? '');
    $lname = mysqli_real_escape_string($connect, $_POST['lname'] ?? '');
    $email = mysqli_real_escape_string($connect, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = mysqli_real_escape_string($connect, $_POST['phone'] ?? '');
    $address = mysqli_real_escape_string($connect, $_POST['address'] ?? '');
    $role = mysqli_real_escape_string($connect, $_POST['role'] ?? '');
    
    // Store form data for re-population
    $formData = [
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'role' => $role
    ];
    
    // Validation
    $errors = [];
    
    if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
        $errors[] = "All required fields must be filled!";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
    
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters!";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $check_email = "SELECT user_id FROM users WHERE email = '$email'";
        $email_result = mysqli_query($connect, $check_email);
        if (mysqli_num_rows($email_result) > 0) {
            $errors[] = "Email already registered!";
        }
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert query
        $sql = "INSERT INTO users (fname, lname, email, password, phone, address, role) 
                VALUES ('$fname', '$lname', '$email', '$hashed_password', '$phone', '$address', '$role')";
        
        if (mysqli_query($connect, $sql)) {
            $success = ucfirst($role) . " registered successfully!";
            // Clear form data on success
            $formData = [];
        } else {
            $error = "Registration failed: " . mysqli_error($connect);
        }
    } else {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration - Ella Kitchen Cafe Admin</title>
    
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

                <li><a href="users.php" class="nav-item">
                    <i class="fas fa-users"></i> Users</a></li>

                <li class="active"><a href="Registration.php" class="nav-item">
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
            <h1>Staff Registration</h1>
            <div class="admin-header-right">
                <div class="profile-box">
                    <div class="avatar"><i class="fa-solid fa-user"></i></div>
                    <p class="username">Jossikalex</p>
                </div>
            </div>
        </header>
        
        <div class="admin-content">
            <!-- Display Messages -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="register-container">
                <!-- Waiter Registration Form -->
                <div class="register-box">
                    <h2><i class="fas fa-concierge-bell"></i> Register Waiter</h2>
                    <form class="form-container" method="POST" action="">
                        <input type="hidden" name="role" value="waiter">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="waiter_fname">First Name *</label>
                                <input type="text" id="waiter_fname" name="fname" 
                                       placeholder="Enter first name" required
                                       value="<?php echo $formData['role'] === 'waiter' ? htmlspecialchars($formData['fname']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="waiter_lname">Last Name *</label>
                                <input type="text" id="waiter_lname" name="lname" 
                                       placeholder="Enter last name" required
                                       value="<?php echo $formData['role'] === 'waiter' ? htmlspecialchars($formData['lname']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="waiter_email">Email Address *</label>
                                <input type="email" id="waiter_email" name="email" 
                                       placeholder="waiter@example.com" required
                                       value="<?php echo $formData['role'] === 'waiter' ? htmlspecialchars($formData['email']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="waiter_phone">Phone Number</label>
                                <input type="tel" id="waiter_phone" name="phone" 
                                       placeholder="+251 (9) 123-4567"
                                       value="<?php echo $formData['role'] === 'waiter' ? htmlspecialchars($formData['phone']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="waiter_password">Password *</label>
                                <input type="password" id="waiter_password" name="password" 
                                       placeholder="Create password (min. 6 characters)" required>
                            </div>
                            <div class="form-group">
                                <label for="waiter_confirm_password">Confirm Password *</label>
                                <input type="password" id="waiter_confirm_password" name="confirm_password" 
                                       placeholder="Confirm password" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="waiter_address">Address</label>
                            <textarea id="waiter_address" name="address" rows="2" 
                                      placeholder="Enter address"><?php echo $formData['role'] === 'waiter' ? htmlspecialchars($formData['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Register Waiter
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-eraser"></i> Clear Form
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Delivery Person Registration Form -->
                <div class="register-box">
                    <h2><i class="fas fa-motorcycle"></i> Register Delivery Person</h2>
                    <form class="form-container" method="POST" action="">
                        <input type="hidden" name="role" value="delivery">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="delivery_fname">First Name *</label>
                                <input type="text" id="delivery_fname" name="fname" 
                                       placeholder="Enter first name" required
                                       value="<?php echo $formData['role'] === 'delivery' ? htmlspecialchars($formData['fname']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="delivery_lname">Last Name *</label>
                                <input type="text" id="delivery_lname" name="lname" 
                                       placeholder="Enter last name" required
                                       value="<?php echo $formData['role'] === 'delivery' ? htmlspecialchars($formData['lname']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="delivery_email">Email Address *</label>
                                <input type="email" id="delivery_email" name="email" 
                                       placeholder="delivery@example.com" required
                                       value="<?php echo $formData['role'] === 'delivery' ? htmlspecialchars($formData['email']) : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="delivery_phone">Phone Number *</label>
                                <input type="tel" id="delivery_phone" name="phone" 
                                       placeholder="+251 (9) 123-4567" required
                                       value="<?php echo $formData['role'] === 'delivery' ? htmlspecialchars($formData['phone']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="delivery_password">Password *</label>
                                <input type="password" id="delivery_password" name="password" 
                                       placeholder="Create password (min. 6 characters)" required>
                            </div>
                            <div class="form-group">
                                <label for="delivery_confirm_password">Confirm Password *</label>
                                <input type="password" id="delivery_confirm_password" name="confirm_password" 
                                       placeholder="Confirm password" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="delivery_address">Address *</label>
                            <textarea id="delivery_address" name="address" rows="2" 
                                      placeholder="Enter address" required><?php echo $formData['role'] === 'delivery' ? htmlspecialchars($formData['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Register Delivery Person
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-eraser"></i> Clear Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Registration Tips -->
            <div class="content-section" style="margin-top: 30px;">
                <h3><i class="fas fa-lightbulb"></i> Registration Tips</h3>
                <ul style="list-style: none; padding: 0; color: #666;">
                    <li style="padding: 5px 0;"><i class="fas fa-check-circle" style="color: #28a745;"></i> 
                        Passwords must be at least 6 characters long</li>
                    <li style="padding: 5px 0;"><i class="fas fa-check-circle" style="color: #28a745;"></i> 
                        Delivery persons require phone number and address for contact</li>
                    <li style="padding: 5px 0;"><i class="fas fa-check-circle" style="color: #28a745;"></i> 
                        Registered staff can login with their email and password</li>
                    <li style="padding: 5px 0;"><i class="fas fa-check-circle" style="color: #28a745;"></i> 
                        View all registered staff in the <a href="users.php">Users</a> page</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="Javascript/admin.js"></script>
    
    <script>
    // Registration page specific functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Password strength indicator
        const passwordInputs = document.querySelectorAll('input[type="password"]');
        
        passwordInputs.forEach(input => {
            input.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);
                
                // Optional: Add visual feedback for password strength
                if (password.length > 0) {
                    if (strength === 'weak') {
                        this.style.borderColor = '#dc3545';
                    } else if (strength === 'medium') {
                        this.style.borderColor = '#ffc107';
                    } else if (strength === 'strong') {
                        this.style.borderColor = '#28a745';
                    }
                } else {
                    this.style.borderColor = '';
                }
            });
        });
        
        /**
         * Check password strength
         * @param {string} password - Password to check
         * @returns {string} - Strength level (weak, medium, strong)
         */
        function checkPasswordStrength(password) {
            if (password.length < 6) return 'weak';
            if (password.length < 8) return 'medium';
            
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            
            let strength = 0;
            if (hasUpperCase) strength++;
            if (hasLowerCase) strength++;
            if (hasNumbers) strength++;
            if (hasSpecial) strength++;
            
            if (strength < 2) return 'medium';
            if (strength < 4) return 'strong';
            return 'very-strong';
        }
        
        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
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
        });
    });
    </script>
</body>
</html>
/**
 * ELLA KITCHEN CAFE - ADMIN PANEL JAVASCRIPT
 * Version: 2.0 | Clean & Professional
 * Description: Core admin functionality for all admin pages
 */

// ============================================================================
// 1. GLOBAL ADMIN UTILITIES
// ============================================================================

/**
 * Show toast notification
 * @param {string} message - Notification message
 * @param {string} type - Notification type (success, error, info)
 */
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Style toast
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#007bff'};
        color: white;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

/**
 * Confirm action dialog
 * @param {string} message - Confirmation message
 * @returns {Promise<boolean>} - True if confirmed, false if cancelled
 */
function confirmAction(message) {
    return Swal.fire({
        title: 'Confirm Action',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Continue',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#f6b11a',
        cancelButtonColor: '#6c757d',
    }).then((result) => result.isConfirmed);
}

/**
 * Validate form inputs
 * @param {HTMLFormElement} form - Form element to validate
 * @returns {boolean} - True if valid, false if invalid
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            input.style.borderColor = '';
        }
    });
    
    // Password confirmation check
    const password = form.querySelector('input[name="password"]');
    const confirmPassword = form.querySelector('input[name="confirm_password"]');
    
    if (password && confirmPassword && password.value !== confirmPassword.value) {
        password.style.borderColor = '#dc3545';
        confirmPassword.style.borderColor = '#dc3545';
        showToast('Passwords do not match!', 'error');
        isValid = false;
    }
    
    return isValid;
}

// ============================================================================
// 2. LOGIN PAGE FUNCTIONALITY
// ============================================================================

/**
 * Initialize login page functionality
 */
function initLoginPage() {
    const openLoginBtn = document.getElementById('openLoginBtn');
    const closeLoginBtn = document.getElementById('closeLoginBtn');
    const loginModal = document.getElementById('loginModal');
    const loginForm = document.getElementById('loginForm');
    const submitLoginBtn = document.getElementById('submitLoginBtn');
    
    if (!openLoginBtn || !loginModal) return;
    
    // Open login modal
    openLoginBtn.addEventListener('click', () => {
        loginModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });
    
    // Close login modal
    closeLoginBtn?.addEventListener('click', () => {
        loginModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });
    
    // Close modal when clicking outside
    loginModal.addEventListener('click', (e) => {
        if (e.target === loginModal) {
            loginModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
    
    // Handle form submission
    loginForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const username = document.getElementById('username')?.value;
        const password = document.getElementById('password')?.value;
        
        if (!username || !password) {
            showToast('Please enter both username and password', 'error');
            return;
        }
        
        // Show loading state
        const originalText = submitLoginBtn.innerHTML;
        submitLoginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
        submitLoginBtn.disabled = true;
        
        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // Demo authentication (replace with real API call)
            if (username && password) {
                showToast('Login successful! Redirecting...', 'success');
                
                // Store user session (in real app, use proper session management)
                sessionStorage.setItem('adminLoggedIn', 'true');
                sessionStorage.setItem('adminUsername', username);
                
                // Redirect to dashboard after delay
                setTimeout(() => {
                    window.location.href = 'Dashboard.html';
                }, 1000);
            } else {
                showToast('Invalid credentials', 'error');
                submitLoginBtn.innerHTML = originalText;
                submitLoginBtn.disabled = false;
            }
        } catch (error) {
            showToast('Login failed. Please try again.', 'error');
            submitLoginBtn.innerHTML = originalText;
            submitLoginBtn.disabled = false;
        }
    });
}

// ============================================================================
// 3. ORDERS PAGE FUNCTIONALITY
// ============================================================================

/**
 * Initialize orders page functionality
 */
function initOrdersPage() {
    // Order status change handlers
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async function() {
            const orderId = this.getAttribute('data-order');
            const newStatus = this.value;
            
            const confirmed = await confirmAction(`Update order #${orderId} status to "${newStatus}"?`);
            if (confirmed) {
                // Simulate API call
                showToast(`Order #${orderId} status updated to ${newStatus}`, 'success');
                
                // Update row styling
                const row = this.closest('tr');
                const statusCell = row.querySelector('td:nth-child(6)');
                if (statusCell.querySelector('.status')) {
                    statusCell.querySelector('.status').className = `status ${newStatus}`;
                    statusCell.querySelector('.status').textContent = 
                        newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                }
            } else {
                // Reset to original value
                this.value = this.dataset.originalValue || this.value;
            }
            
            // Store original value for next change
            this.dataset.originalValue = newStatus;
        });
    });
    
    // View order details
    document.querySelectorAll('.btn-primary:not([type="submit"])').forEach(button => {
        if (button.textContent.includes('View')) {
            button.addEventListener('click', function() {
                const orderId = this.closest('tr').querySelector('td:first-child').textContent;
                alert(`Viewing order details for ${orderId}\n\nThis would open a detailed view modal in a production application.`);
            });
        }
    });
    
    // Delete order buttons
    document.querySelectorAll('.btn-danger').forEach(button => {
        if (!button.closest('form')) {
            button.addEventListener('click', async function() {
                const row = this.closest('tr');
                const orderId = row.querySelector('td:first-child').textContent;
                
                const confirmed = await confirmAction(`Delete order ${orderId}? This action cannot be undone.`);
                if (confirmed) {
                    row.remove();
                    showToast(`Order ${orderId} deleted`, 'error');
                    updateOrderSummary();
                }
            });
        }
    });
    
    // Filter functionality
    const filterBtn = document.querySelector('.filter-controls .btn-primary');
    const resetBtn = document.querySelector('.filter-controls .btn-secondary');
    
    filterBtn?.addEventListener('click', () => {
        const status = document.querySelector('.filter-select')?.value || 'all';
        const date = document.querySelector('.filter-date')?.value;
        
        let message = 'Filtering orders';
        if (status !== 'all') message += ` by status: ${status}`;
        if (date) message += ` on date: ${date}`;
        
        showToast(message);
        // In production: Implement actual filtering
    });
    
    resetBtn?.addEventListener('click', () => {
        document.querySelector('.filter-select').value = 'all';
        document.querySelector('.filter-date').value = '';
        showToast('Filters reset');
        // In production: Reset table to show all orders
    });
    
    /**
     * Update order summary statistics
     */
    function updateOrderSummary() {
        const rows = document.querySelectorAll('.admin-table tbody tr');
        const today = new Date().toLocaleDateString('en-CA');
        
        const todayOrders = Array.from(rows).filter(row => {
            const dateText = row.querySelector('td:nth-child(3)').textContent;
            return dateText.includes(today);
        }).length;
        
        const pendingOrders = Array.from(rows).filter(row => {
            const status = row.querySelector('.status-select').value;
            return status === 'pending';
        }).length;
        
        // Update summary display if exists
        const summaryElement = document.querySelector('.order-summary');
        if (summaryElement) {
            summaryElement.textContent = 
                `Today: ${todayOrders} orders | Pending: ${pendingOrders} orders`;
        }
    }
    
    // Initial summary update
    updateOrderSummary();
}

// ============================================================================
// 4. DASHBOARD FUNCTIONALITY
// ============================================================================

/**
 * Initialize dashboard functionality
 */
function initDashboard() {
    // View order buttons
    document.querySelectorAll('.btn-small:not([type])').forEach(button => {
        if (button.textContent.includes('View')) {
            button.addEventListener('click', function() {
                const orderId = this.closest('tr').querySelector('td:first-child').textContent;
                showToast(`Viewing order ${orderId}`);
                // In production: Show order details modal
            });
        }
    });
    
    // Simulate live statistics updates
    function updateLiveStatistics() {
        const statCards = document.querySelectorAll('.stat-card');
        
        if (statCards.length >= 4) {
            // Randomly update total orders
            const ordersElement = statCards[0].querySelector('.stat-number');
            let orders = parseInt(ordersElement.textContent) || 0;
            orders += Math.floor(Math.random() * 3);
            ordersElement.textContent = orders;
            
            // Update trends
            const trends = document.querySelectorAll('.stat-trend');
            trends.forEach(trend => {
                const change = Math.random() > 0.5 ? '↑' : '↓';
                const percent = Math.floor(Math.random() * 20) + 1;
                trend.textContent = `${change} ${percent}% this hour`;
            });
        }
    }
    
    // Update statistics every 30 seconds
    setInterval(updateLiveStatistics, 30000);
    
    // Logout functionality
    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        confirmLogout();
    });
}

// ============================================================================
// 5. LOGOUT FUNCTIONALITY
// ============================================================================

/**
 * Handle user logout with confirmation
 */
function confirmLogout() {
    Swal.fire({
        title: 'Logout?',
        html: `
            <div class="logout-icon" style="font-size: 50px; color: #dc3545; margin-bottom: 20px;">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <p>Are you sure you want to logout from the admin panel?</p>
        `,
        showCancelButton: true,
        confirmButtonText: 'Yes, Logout',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        backdrop: 'rgba(0,0,0,0.7)',
        allowOutsideClick: false,
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Logging out...',
                html: `
                    <div style="font-size: 40px; color: #f6b11a; margin-bottom: 20px;">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <p>Please wait while we log you out</p>
                `,
                showConfirmButton: false,
                allowOutsideClick: false,
            });
            
            // Simulate logout process
            setTimeout(() => {
                // Clear session data
                sessionStorage.removeItem('adminLoggedIn');
                sessionStorage.removeItem('adminUsername');
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Logged out!',
                    text: 'You have been successfully logged out.',
                    timer: 1500,
                    showConfirmButton: false,
                    willClose: () => {
                        // Redirect to login page
                        window.location.href = 'Loginpage.html';
                    }
                });
            }, 1500);
        }
    });
}

// ============================================================================
// 6. MENU MANAGEMENT FUNCTIONALITY
// ============================================================================

/**
 * Edit menu item (for update-menu.php)
 * @param {HTMLElement} button - Edit button element
 */
function editMenuItem(button) {
    const btn = button.tagName === 'BUTTON' ? button : button.closest('.edit-btn');
    if (!btn) return;
    
    // Get data from attributes
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-name');
    const description = btn.getAttribute('data-description');
    const price = btn.getAttribute('data-price');
    const category = btn.getAttribute('data-category');
    const image = btn.getAttribute('data-image');
    
    // Populate form fields
    document.getElementById('edit_id').value = id || '';
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_price').value = price || '0';
    document.getElementById('edit_category').value = category || '';
    document.getElementById('edit_image_path').value = image || '';
    
    // Show image preview
    const preview = document.getElementById('edit_image_preview');
    if (image && image.trim() !== '') {
        preview.src = image;
        preview.style.display = 'block';
        preview.onerror = () => {
            preview.style.display = 'none';
            showToast('Could not load image from URL', 'error');
        };
    } else {
        preview.style.display = 'none';
    }
    
    // Show modal
    const modal = document.getElementById('editModal');
    modal.classList.add('show');
    modal.style.display = 'flex';
}

/**
 * Close edit modal
 */
function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.remove('show');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

/**
 * Update image preview
 * @param {string} imageUrl - Image URL
 * @param {string} previewId - Preview element ID
 */
function updateImagePreview(imageUrl, previewId) {
    const preview = document.getElementById(previewId);
    if (!preview) return;
    
    if (imageUrl && imageUrl.trim() !== '') {
        preview.src = imageUrl;
        preview.style.display = 'block';
        preview.onerror = () => {
            preview.style.display = 'none';
            showToast('Could not load image from URL', 'error');
        };
    } else {
        preview.style.display = 'none';
    }
}

/**
 * Confirm item deletion
 * @param {string} itemName - Name of item to delete
 * @returns {boolean} - True if confirmed
 */
function confirmDelete(itemName) {
    return confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`);
}

// ============================================================================
// 7. USER MANAGEMENT FUNCTIONALITY
// ============================================================================

/**
 * View user details
 * @param {number} userId - User ID
 */
function viewUser(userId) {
    showToast(`Viewing user ID: ${userId}`, 'info');
    // In production: Open user details modal or page
}

// ============================================================================
// 8. INITIALIZATION
// ============================================================================

/**
 * Main initialization function
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('Ella Kitchen Cafe Admin Panel initialized');
    
    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.parentNode.removeChild(alert);
                }
            }, 500);
        });
    }, 5000);
    
    // Password confirmation validation
    document.querySelectorAll('form').forEach(form => {
        const password = form.querySelector('input[name="password"]');
        const confirmPassword = form.querySelector('input[name="confirm_password"]');
        
        if (password && confirmPassword) {
            form.addEventListener('submit', function(e) {
                if (password.value !== confirmPassword.value) {
                    e.preventDefault();
                    showToast('Passwords do not match!', 'error');
                    password.focus();
                }
            });
        }
    });
    
    // Image preview for forms
    document.getElementById('image_path')?.addEventListener('input', function(e) {
        updateImagePreview(this.value, 'image-preview');
    });
    
    document.getElementById('edit_image_path')?.addEventListener('input', function(e) {
        updateImagePreview(this.value, 'edit_image_preview');
    });
    
    // Add event listeners to all edit buttons
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            editMenuItem(this);
        });
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEditModal();
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('editModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
    
    // Page-specific initializations
    const currentPage = window.location.pathname.split('/').pop();
    
    switch(currentPage) {
        case 'Loginpage.html':
            initLoginPage();
            break;
        case 'orders.html':
            initOrdersPage();
            break;
        case 'Dashboard.html':
            initDashboard();
            break;
        case 'update-menu.php':
            // Menu page already has PHP functionality
            break;
        case 'users.php':
            // User management page
            break;
        case 'Registration.php':
            // Registration page
            break;
        case 'report.html':
            // Report page
            break;
    }
});

// ============================================================================
// 9. GLOBAL EXPORTS (for debugging/development)
// ============================================================================
window.adminUtils = {
    showToast,
    confirmAction,
    validateForm,
    viewUser,
    editMenuItem,
    confirmLogout
};

console.log('Admin utilities loaded. Use window.adminUtils for debugging.');
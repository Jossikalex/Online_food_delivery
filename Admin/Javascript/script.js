// admin.js - Shared admin panel functionality

// Notification system
class NotificationSystem {
    constructor() {
        this.notifications = [];
        this.init();
    }
    
    init() {
        // Load notifications from localStorage
        const saved = localStorage.getItem('admin_notifications');
        if (saved) {
            this.notifications = JSON.parse(saved);
            this.updateBadge();
        }
        
        // Setup notification icon click
        const notificationIcon = document.querySelector('.header-icon');
        if (notificationIcon) {
            notificationIcon.addEventListener('click', () => this.showNotifications());
        }
    }
    
    updateBadge() {
        const badge = document.querySelector('.badge');
        if (badge) {
            badge.textContent = this.notifications.length;
            badge.style.display = this.notifications.length > 0 ? 'block' : 'none';
        }
    }
    
    addNotification(message, type = 'info') {
        const notification = {
            id: Date.now(),
            message,
            type,
            timestamp: new Date().toLocaleTimeString(),
            read: false
        };
        
        this.notifications.unshift(notification);
        this.save();
        this.updateBadge();
    }
    
    markAsRead(id) {
        const notification = this.notifications.find(n => n.id === id);
        if (notification) {
            notification.read = true;
            this.save();
            this.updateBadge();
        }
    }
    
    showNotifications() {
        // Create notification modal
        const modal = document.createElement('div');
        modal.className = 'notification-modal';
        modal.innerHTML = `
            <div class="notification-overlay" onclick="this.parentElement.remove()"></div>
            <div class="notification-panel">
                <div class="notification-header">
                    <h3>Notifications (${this.notifications.length})</h3>
                    <button onclick="this.parentElement.parentElement.parentElement.remove()">×</button>
                </div>
                <div class="notification-list">
                    ${this.notifications.length > 0 ? 
                        this.notifications.map(n => `
                            <div class="notification-item ${n.read ? 'read' : 'unread'}" onclick="adminNotifications.markAsRead(${n.id})">
                                <span class="notification-type ${n.type}">${n.type}</span>
                                <p>${n.message}</p>
                                <small>${n.timestamp}</small>
                            </div>
                        `).join('') :
                        '<p class="no-notifications">No notifications</p>'
                    }
                </div>
                <div class="notification-actions">
                    <button onclick="adminNotifications.clearAll()">Clear All</button>
                </div>
            </div>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .notification-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
            }
            .notification-overlay {
                position: absolute;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
            }
            .notification-panel {
                position: absolute;
                right: 20px;
                top: 60px;
                width: 350px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
                max-height: 500px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
            }
            .notification-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 15px;
                background: #f6b11a;
                color: white;
            }
            .notification-header button {
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
            }
            .notification-list {
                flex: 1;
                overflow-y: auto;
                padding: 10px;
            }
            .notification-item {
                padding: 10px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                transition: background 0.3s;
            }
            .notification-item:hover {
                background: #f8f9fa;
            }
            .notification-item.unread {
                background: #f0f8ff;
            }
            .notification-type {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 10px;
                font-size: 12px;
                margin-right: 10px;
            }
            .notification-type.info { background: #d1ecf1; color: #0c5460; }
            .notification-type.warning { background: #fff3cd; color: #856404; }
            .notification-type.success { background: #d4edda; color: #155724; }
            .no-notifications {
                text-align: center;
                padding: 20px;
                color: #666;
            }
            .notification-actions {
                padding: 10px;
                text-align: center;
                border-top: 1px solid #eee;
            }
            .notification-actions button {
                padding: 8px 20px;
                background: #dc3545;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(modal);
    }
    
    clearAll() {
        this.notifications = [];
        this.save();
        this.updateBadge();
        document.querySelector('.notification-modal')?.remove();
    }
    
    save() {
        localStorage.setItem('admin_notifications', JSON.stringify(this.notifications));
    }
}

// Initialize notification system
const adminNotifications = new NotificationSystem();

// Add some sample notifications
setTimeout(() => {
    adminNotifications.addNotification('New order received: #ORD-1006', 'info');
    adminNotifications.addNotification('User Yosef registered successfully', 'success');
    adminNotifications.addNotification('Low stock alert: Burger buns', 'warning');
}, 1000);

// Form validation
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            input.style.borderColor = '#ddd';
        }
    });
    
    // Password confirmation check
    const password = form.querySelector('input[type="password"]');
    const confirmPassword = form.querySelector('input[placeholder*="Confirm"]');
    if (password && confirmPassword && password.value !== confirmPassword.value) {
        alert('Passwords do not match!');
        password.style.borderColor = '#dc3545';
        confirmPassword.style.borderColor = '#dc3545';
        isValid = false;
    }
    
    return isValid;
}

// Show toast message
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#f6b11a'};
        color: white;
        border-radius: 5px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add animation styles
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

// Confirmation dialog
function confirmAction(message) {
    return new Promise((resolve) => {
        const modal = document.createElement('div');
        modal.className = 'confirm-modal';
        modal.innerHTML = `
            <div class="confirm-overlay"></div>
            <div class="confirm-dialog">
                <p>${message}</p>
                <div class="confirm-buttons">
                    <button class="btn btn-secondary" onclick="this.closest('.confirm-modal').remove(); resolve(false)">Cancel</button>
                    <button class="btn btn-danger" onclick="this.closest('.confirm-modal').remove(); resolve(true)">Confirm</button>
                </div>
            </div>
        `;
        
        // Add styles
        const modalStyle = document.createElement('style');
        modalStyle.textContent = `
            .confirm-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .confirm-overlay {
                position: absolute;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
            }
            .confirm-dialog {
                position: relative;
                background: white;
                padding: 30px;
                border-radius: 10px;
                min-width: 300px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            }
            .confirm-buttons {
                display: flex;
                gap: 10px;
                justify-content: flex-end;
                margin-top: 20px;
            }
        `;
        
        document.head.appendChild(modalStyle);
        document.body.appendChild(modal);
    });
}

// Export for global use
window.adminNotifications = adminNotifications;
window.validateForm = validateForm;
window.showToast = showToast;
window.confirmAction = confirmAction;

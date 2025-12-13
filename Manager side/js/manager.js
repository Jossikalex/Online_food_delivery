// Toggle sidebar on mobile
const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

if (menuToggle) {
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
}

// Close sidebar when clicking outside on mobile
document.addEventListener('click', (e) => {
    if (window.innerWidth <= 768) {
        if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});

// Profile form submission
const profileForm = document.getElementById('profileForm');
if (profileForm) {
    profileForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Profile updated successfully!');
    });
}

// Application form submission
const applicationForm = document.getElementById('applicationForm');
if (applicationForm) {
    applicationForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Application submitted successfully!');
        applicationForm.reset();
    });
}

// Accept complaint buttons
const acceptButtons = document.querySelectorAll('.btn-accept');
acceptButtons.forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Accept this complaint?')) {
            btn.closest('.complaint-card').querySelector('.status-badge').textContent = 'Resolved';
            btn.closest('.complaint-card').querySelector('.status-badge').className = 'status-badge resolved';
            btn.remove();
            alert('Complaint accepted and resolved!');
        }
    });
});
// Homepage interactivity
document.addEventListener('DOMContentLoaded', function() {
    // Login button functionality
    const loginBtn = document.getElementById('loginBtn');
    
    if (loginBtn) {
        loginBtn.addEventListener('click', function() {
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
                // Redirect to login page
                window.location.href = 'login.html';
            }, 150);
        });

        // Add hover sound effect (optional)
        loginBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        loginBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    }

    // Animate hero elements on load
    const heroTitle = document.querySelector('.hero-title');
    const heroTagline = document.querySelector('.hero-tagline');
    const heroFeatures = document.querySelectorAll('.feature');

    if (heroTitle) {
        setTimeout(() => {
            heroTitle.style.opacity = '1';
            heroTitle.style.transform = 'translateY(0)';
        }, 300);
    }

    if (heroTagline) {
        setTimeout(() => {
            heroTagline.style.opacity = '1';
            heroTagline.style.transform = 'translateY(0)';
        }, 600);
    }

    // Animate features with stagger effect
    heroFeatures.forEach((feature, index) => {
        setTimeout(() => {
            feature.style.opacity = '1';
            feature.style.transform = 'translateY(0)';
        }, 900 + (index * 200));
    });

    // Add floating animation to hero icon
    const heroIcon = document.querySelector('.hero-icon');
    if (heroIcon) {
        setInterval(() => {
            heroIcon.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                heroIcon.style.transform = 'translateY(0)';
            }, 1000);
        }, 2000);
    }
});

// Smooth scroll for footer links (if needed)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
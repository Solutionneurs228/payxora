/**
 * PayXora - Custom JavaScript
 * Animations, compteurs, scroll reveal
 */

document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // 1. Scroll Reveal
    // ============================================================
    const revealElements = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));

    // ============================================================
    // 2. Animated Counters
    // ============================================================
    const counters = document.querySelectorAll('.stat-counter');

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = parseInt(entry.target.textContent) || 0;
                animateCounter(entry.target, target, 1500);
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => counterObserver.observe(counter));

    function animateCounter(element, target, duration) {
        const start = 0;
        const startTime = performance.now();

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease out quad
            const easeProgress = 1 - (1 - progress) * (1 - progress);
            const current = Math.floor(start + (target - start) * easeProgress);

            element.textContent = current.toLocaleString('fr-FR');

            if (progress < 1) {
                requestAnimationFrame(update);
            } else {
                element.textContent = target.toLocaleString('fr-FR');
            }
        }

        requestAnimationFrame(update);
    }

    // ============================================================
    // 3. Mobile Menu Toggle
    // ============================================================
    const mobileMenuBtn = document.querySelector('[data-mobile-menu-btn]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // ============================================================
    // 4. Notification Dropdown
    // ============================================================
    const notifBtn = document.querySelector('[data-notif-btn]');
    const notifDropdown = document.querySelector('[data-notif-dropdown]');

    if (notifBtn && notifDropdown) {
        notifBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            notifDropdown.classList.add('hidden');
        });
    }

    // ============================================================
    // 5. Smooth Anchor Links
    // ============================================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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

    // ============================================================
    // 6. Flash Message Auto Dismiss
    // ============================================================
    const flashMessages = document.querySelectorAll('[data-flash-message]');

    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            msg.style.transition = 'all 0.3s ease';
            setTimeout(() => msg.remove(), 300);
        }, 5000);
    });

    // ============================================================
    // 7. Form Validation Visual Feedback
    // ============================================================
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('border-red-500', 'ring-red-500');

                    // Shake animation
                    field.style.animation = 'shake 0.5s ease';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                } else {
                    field.classList.remove('border-red-500', 'ring-red-500');
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    });

    // ============================================================
    // 8. Copy to Clipboard
    // ============================================================
    const copyBtns = document.querySelectorAll('[data-copy]');

    copyBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const text = btn.getAttribute('data-copy');
            navigator.clipboard.writeText(text).then(() => {
                const original = btn.innerHTML;
                btn.innerHTML = '<span class="text-emerald-600">Copie !</span>';
                setTimeout(() => {
                    btn.innerHTML = original;
                }, 2000);
            });
        });
    });

    // ============================================================
    // 9. Lazy Load Images
    // ============================================================
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));

    console.log('PayXora JS loaded successfully');
});

// ============================================================
// Shake Keyframes (injected via JS since we use Tailwind)
// ============================================================
const shakeStyle = document.createElement('style');
shakeStyle.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(shakeStyle);

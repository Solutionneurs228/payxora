// PayXora Custom JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages
    const flashMessages = document.querySelectorAll('[data-flash-auto-hide]');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transform = 'translateY(-10px)';
            setTimeout(() => msg.remove(), 300);
        }, 5000);
    });

    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const displayId = this.dataset.display;
            if (displayId) {
                const display = document.getElementById(displayId);
                if (display && this.files[0]) {
                    display.textContent = 'Fichier selectionne : ' + this.files[0].name;
                    display.classList.add('text-indigo-600');
                }
            }
        });
    });

    // Mobile money provider selection
    const providerRadios = document.querySelectorAll('input[name="provider"]');
    providerRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            providerRadios.forEach(r => {
                const card = r.closest('.provider-card') || r.closest('label').querySelector('div');
                if (card) {
                    if (r.checked) {
                        card.classList.add('selected');
                    } else {
                        card.classList.remove('selected');
                    }
                }
            });
        });
    });

    // Amount calculator for transaction creation
    const amountInput = document.getElementById('amount');
    if (amountInput) {
        const commissionRate = parseFloat(amountInput.dataset.commissionRate) || 3;
        const commissionMin = parseFloat(amountInput.dataset.commissionMin) || 100;
        const commissionMax = parseFloat(amountInput.dataset.commissionMax) || 50000;

        const commissionDisplay = document.getElementById('commission-display');
        const netDisplay = document.getElementById('net-amount-display');

        if (commissionDisplay && netDisplay) {
            amountInput.addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;
                let commission = amount * (commissionRate / 100);
                commission = Math.max(commissionMin, Math.min(commission, commissionMax));
                const net = amount - commission;

                commissionDisplay.textContent = commission.toLocaleString('fr-FR') + ' FCFA';
                netDisplay.textContent = net.toLocaleString('fr-FR') + ' FCFA';
            });
        }
    }

    // Copy to clipboard
    const copyButtons = document.querySelectorAll('[data-copy]');
    copyButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const text = this.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                const original = this.innerHTML;
                this.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => this.innerHTML = original, 2000);
            });
        });
    });

    // Confirm before dangerous actions
    const confirmForms = document.querySelectorAll('[data-confirm]');
    confirmForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const message = this.dataset.confirm || 'Etes-vous sur ?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // Dropdown toggles (fallback for browsers without Alpine.js)
    const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const targetId = this.dataset.dropdownToggle;
            const target = document.getElementById(targetId);
            if (target) {
                target.classList.toggle('hidden');
            }
        });
    });

    document.addEventListener('click', function() {
        document.querySelectorAll('[data-dropdown]').forEach(d => d.classList.add('hidden'));
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Intersection Observer for animations
    const animatedElements = document.querySelectorAll('[data-animate]');
    if (animatedElements.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        animatedElements.forEach(el => observer.observe(el));
    }

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 15);
        });
    });

    // Print transaction
    const printButtons = document.querySelectorAll('[data-print]');
    printButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            window.print();
        });
    });
});

// Alpine.js helpers
window.PayXora = {
    // Format currency
    formatCurrency: function(amount, currency = 'FCFA') {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' ' + currency;
    },

    // Format date
    formatDate: function(date) {
        return new Date(date).toLocaleDateString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    },

    // Debounce function
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
};

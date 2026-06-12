/**
 * Admin JS for eCart Electronics
 * Version: 1.0.0
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar toggle
    initSidebarToggle();
    
    // Initialize dropdowns
    initDropdowns();
    
    // Initialize tooltips
    initTooltips();
    
    // Initialize mark all as read functionality
    initMarkAllAsRead();
    
    // Auto-dismiss alerts after 5 seconds
    autoDismissAlerts();
    
    // Confirm delete actions
    initConfirmDelete();
    
    // Initialize status toggles
    initStatusToggles();
    
    // Initialize image preview
    initImagePreview();
    
    // Initialize form validations
    initFormValidation();
});

/**
 * Sidebar Toggle Functionality
 */
function initSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('expanded');
            updateSidebarState();
        });
    }
    
    if (sidebarToggleMobile) {
        sidebarToggleMobile.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                sidebar.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
                main.classList.toggle('expanded');
                updateSidebarState();
            }
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
            if (!sidebar.contains(event.target) && !sidebarToggleMobile.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
    
    // Load sidebar state from localStorage
    loadSidebarState();
}

/**
 * Update sidebar state in localStorage
 */
function updateSidebarState() {
    const sidebar = document.querySelector('.sidebar');
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

/**
 * Load sidebar state from localStorage
 */
function loadSidebarState() {
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main');
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        main.classList.add('expanded');
    }
}

/**
 * Initialize Bootstrap dropdowns
 */
function initDropdowns() {
    // Enable all dropdowns
    const dropdowns = document.querySelectorAll('.dropdown-toggle');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                e.preventDefault();
                const menu = this.nextElementSibling;
                menu.classList.toggle('show');
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
}

/**
 * Initialize Bootstrap tooltips
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Mark all notifications as read
 */
function initMarkAllAsRead() {
    const markAllReadButtons = document.querySelectorAll('.mark-all-read');
    
    markAllReadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            this.disabled = true;
            
            // Send AJAX request to mark all as read
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove all notification badges
                    document.querySelectorAll('.notification-badge').forEach(badge => {
                        badge.remove();
                    });
                    
                    // Update notification dropdown content
                    const notificationDropdown = document.querySelector('.notification-dropdown .dropdown-body');
                    if (notificationDropdown) {
                        notificationDropdown.innerHTML = `
                            <div class="text-center py-3">
                                <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                <p class="mb-0 text-muted">No new notifications</p>
                            </div>
                        `;
                    }
                    
                    // Show success message
                    toastr.success('All notifications marked as read');
                } else {
                    toastr.error('Failed to mark notifications as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
            })
            .finally(() => {
                // Restore button state
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    });
}

/**
 * Auto-dismiss alerts after 5 seconds
 */
function autoDismissAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });
}

/**
 * Confirm delete actions
 */
function initConfirmDelete() {
    const deleteForms = document.querySelectorAll('form[data-confirm]');
    
    deleteForms.forEach(form => {
        const confirmMessage = form.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
        
        form.addEventListener('submit', function(e) {
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Also handle delete buttons
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (confirm(confirmMessage)) {
                const formId = this.getAttribute('data-form-id');
                if (formId) {
                    document.getElementById(formId).submit();
                } else if (this.getAttribute('href')) {
                    window.location.href = this.getAttribute('href');
                }
            }
        });
    });
}

/**
 * Initialize status toggles (active/inactive switches)
 */
function initStatusToggles() {
    const statusToggles = document.querySelectorAll('.status-toggle');
    
    statusToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const itemId = this.getAttribute('data-id');
            const itemType = this.getAttribute('data-type');
            const url = this.getAttribute('data-url');
            const checked = this.checked;
            
            // Show loading state
            this.disabled = true;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    id: itemId,
                    status: checked ? 'active' : 'inactive'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success(`Status updated successfully`);
                } else {
                    toastr.error('Failed to update status');
                    // Revert toggle
                    this.checked = !checked;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred');
                // Revert toggle
                this.checked = !checked;
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
}

/**
 * Initialize image preview for file inputs
 */
function initImagePreview() {
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    
    imageInputs.forEach(input => {
        input.addEventListener('change', function() {
            const previewId = this.getAttribute('data-preview');
            const previewElement = document.getElementById(previewId);
            
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (previewElement) {
                        previewElement.src = e.target.result;
                        previewElement.style.display = 'block';
                    }
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    });
}

/**
 * Initialize form validations
 */
function initFormValidation() {
    // Add Bootstrap validation classes
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    }).format(amount);
}

/**
 * Format date
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-IN', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Debounce function for performance
 */
function debounce(func, wait) {
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

/**
 * Throttle function for performance
 */
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Show loading overlay
 */
function showLoading() {
    let overlay = document.getElementById('loading-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loading-overlay';
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
        overlay.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

/**
 * Hide loading overlay
 */
function hideLoading() {
    const overlay = document.getElementById('loading-overlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * Copy to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        toastr.success('Copied to clipboard');
    }).catch(err => {
        console.error('Failed to copy: ', err);
        toastr.error('Failed to copy to clipboard');
    });
}

/**
 * Initialize clipboard buttons
 */
function initClipboard() {
    const copyButtons = document.querySelectorAll('.btn-copy');
    
    copyButtons.forEach(button => {
        button.addEventListener('click', function() {
            const text = this.getAttribute('data-copy') || this.previousElementSibling?.textContent;
            if (text) {
                copyToClipboard(text.trim());
            }
        });
    });
}

// Initialize clipboard on page load
document.addEventListener('DOMContentLoaded', initClipboard);

// Make functions available globally
window.admin = {
    showLoading,
    hideLoading,
    formatCurrency,
    formatDate,
    copyToClipboard,
    initClipboard
};
// Admin Theme Controller
class AdminTheme {
    constructor() {
        this.currentTheme = localStorage.getItem('admin-theme') || 'light';
        this.notifications = [];
        this.init();
    }

    init() {
        this.setTheme(this.currentTheme);
        this.initThemeToggle();
        this.initNotifications();
        this.loadNotifications();
    }

    setTheme(theme) {
        console.log('Setting theme to:', theme); // Debug log
        document.documentElement.setAttribute('data-theme', theme);
        this.currentTheme = theme;
        localStorage.setItem('admin-theme', theme);
        this.updateThemeIcon();
        
        // Force recomputation of styles
        document.body.offsetHeight;
    }

    toggleTheme() {
        const newTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        console.log('Toggling theme from', this.currentTheme, 'to', newTheme); // Debug log
        this.setTheme(newTheme);
        
        // Add a smooth transition effect
        document.body.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    }

    updateThemeIcon() {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) {
            console.warn('Theme toggle button not found'); // Debug log
            return;
        }

        const sunIcon = themeToggle.querySelector('.sun-icon');
        const moonIcon = themeToggle.querySelector('.moon-icon');

        if (!sunIcon || !moonIcon) {
            console.warn('Theme icons not found'); // Debug log
            return;
        }

        if (this.currentTheme === 'dark') {
            sunIcon.style.display = 'block';
            moonIcon.style.display = 'none';
            themeToggle.setAttribute('title', 'Switch to Light Mode');
        } else {
            sunIcon.style.display = 'none';
            moonIcon.style.display = 'block';
            themeToggle.setAttribute('title', 'Switch to Dark Mode');
        }
        
        console.log('Theme icon updated for theme:', this.currentTheme); // Debug log
    }

    initThemeToggle() {
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => this.toggleTheme());
        }
    }

    initNotifications() {
        const notificationBtn = document.getElementById('notification-btn');
        const notificationDropdown = document.getElementById('notification-dropdown');
        
        if (notificationBtn && notificationDropdown) {
            notificationBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.toggleNotificationDropdown();
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                    this.hideNotificationDropdown();
                }
            });
        }
    }

    toggleNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        if (dropdown.classList.contains('show')) {
            this.hideNotificationDropdown();
        } else {
            this.showNotificationDropdown();
        }
    }

    showNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.add('show');
    }

    hideNotificationDropdown() {
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.remove('show');
    }

    async loadNotifications() {
        try {
            const response = await fetch('/admin/notifications');
            const notifications = await response.json();
            this.notifications = notifications;
            this.updateNotificationBadge();
            this.renderNotifications();
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    updateNotificationBadge() {
        const badge = document.getElementById('notification-badge');
        const unreadCount = this.notifications.filter(n => !n.read).length;
        
        if (badge) {
            if (unreadCount > 0) {
                badge.textContent = unreadCount > 9 ? '9+' : unreadCount;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    renderNotifications() {
        const container = document.getElementById('notification-list');
        if (!container) return;

        if (this.notifications.length === 0) {
            container.innerHTML = `
                <div class="notification-item text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                    </svg>
                    <p class="text-gray-500">No notifications</p>
                </div>
            `;
            return;
        }

        container.innerHTML = this.notifications.map(notification => `
            <div class="notification-item ${notification.read ? 'opacity-60' : ''}" data-id="${notification.id}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        ${this.getNotificationIcon(notification.type, notification.icon)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">${notification.title}</h4>
                            <span class="text-xs text-gray-500">${notification.time}</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">${notification.message}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="admin-badge admin-badge-${notification.type} text-xs">${notification.type}</span>
                            ${!notification.read ? `
                                <button onclick="adminTheme.markAsRead(${notification.id})" class="text-xs text-blue-600 hover:text-blue-800">
                                    Mark as read
                                </button>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    getNotificationIcon(type, icon) {
        const iconClasses = {
            'info': 'text-blue-500',
            'success': 'text-green-500',
            'warning': 'text-yellow-500',
            'danger': 'text-red-500'
        };

        const iconPaths = {
            'user-add': 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z',
            'exclamation-triangle': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.95-.833-2.72 0L4.094 16.5c-.77.833.192 2.5 1.732 2.5z',
            'server': 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01',
            'cash': 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
            'shield-exclamation': 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.95-.833-2.72 0L4.094 16.5c-.77.833.192 2.5 1.732 2.5z'
        };

        return `
            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <svg class="w-4 h-4 ${iconClasses[type]}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPaths[icon] || iconPaths['exclamation-triangle']}"></path>
                </svg>
            </div>
        `;
    }

    async markAsRead(id) {
        try {
            const response = await fetch(`/admin/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                // Update notification in local array
                const notification = this.notifications.find(n => n.id === id);
                if (notification) {
                    notification.read = true;
                }
                this.updateNotificationBadge();
                this.renderNotifications();
            }
        } catch (error) {
            console.error('Failed to mark notification as read:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/admin/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                // Mark all notifications as read
                this.notifications.forEach(n => n.read = true);
                this.updateNotificationBadge();
                this.renderNotifications();
            }
        } catch (error) {
            console.error('Failed to mark all notifications as read:', error);
        }
    }

    // Flash message system
    showFlash(message, type = 'info', duration = 5000) {
        const flashContainer = document.getElementById('flash-container') || this.createFlashContainer();
        
        const flash = document.createElement('div');
        flash.className = `admin-alert admin-alert-${type} flash-message`;
        flash.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-current">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        flashContainer.appendChild(flash);

        // Auto remove after duration
        setTimeout(() => {
            if (flash.parentElement) {
                flash.remove();
            }
        }, duration);
    }

    createFlashContainer() {
        const container = document.createElement('div');
        container.id = 'flash-container';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }
}

// Initialize admin theme when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.adminTheme = new AdminTheme();
});

// Utility function for AJAX requests
function makeRequest(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    return fetch(url, { ...defaultOptions, ...options });
}

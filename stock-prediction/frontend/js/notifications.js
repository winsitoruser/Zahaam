/**
 * Notifications.js - Handles notification functionality for ZAHAAM Stock Prediction platform
 * Includes functions for fetching, displaying, and managing user notifications
 */

class NotificationManager {
    constructor() {
        this.notifications = [];
        this.unreadCount = 0;
        this.notificationContainer = document.getElementById('notification-dropdown');
        this.notificationBadge = document.getElementById('notification-badge');
        this.notificationToggle = document.getElementById('notification-toggle');
        this.userId = localStorage.getItem('user_id') || null; // Get user ID from localStorage if available
        
        this.init();
    }
    
    /**
     * Initialize the notification system
     */
    init() {
        // Set up event listeners
        if (this.notificationToggle) {
            this.notificationToggle.addEventListener('click', () => this.toggleNotifications());
        }
        
        // Add mark-all-read button
        if (this.notificationContainer) {
            const markAllReadBtn = document.createElement('button');
            markAllReadBtn.className = 'btn btn-sm btn-light w-100 mb-2';
            markAllReadBtn.innerText = 'Tandai Semua Dibaca';
            markAllReadBtn.addEventListener('click', () => this.markAllAsRead());
            
            // Insert at the top
            const firstChild = this.notificationContainer.firstChild;
            this.notificationContainer.insertBefore(markAllReadBtn, firstChild);
        }
        
        // Fetch notifications on initialization
        this.fetchNotifications();
        
        // Set up interval to check for new notifications every minute
        setInterval(() => this.fetchNotifications(), 60000);
    }
    
    /**
     * Fetch notifications from the API
     */
    async fetchNotifications() {
        try {
            // Construct query parameters
            const params = new URLSearchParams();
            if (this.userId) {
                params.append('user_id', this.userId);
            }
            params.append('limit', 10); // Limit to 10 notifications
            
            const response = await fetch(`/api/notifications?${params.toString()}`);
            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }
            
            const data = await response.json();
            this.notifications = data.notifications;
            this.unreadCount = data.unread_count;
            
            // Update the UI
            this.updateNotificationBadge();
            this.renderNotifications();
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }
    
    /**
     * Update the notification badge with the unread count
     */
    updateNotificationBadge() {
        if (this.notificationBadge) {
            if (this.unreadCount > 0) {
                this.notificationBadge.innerText = this.unreadCount;
                this.notificationBadge.classList.remove('d-none');
            } else {
                this.notificationBadge.classList.add('d-none');
            }
        }
    }
    
    /**
     * Render notifications in the dropdown
     */
    renderNotifications() {
        if (!this.notificationContainer) return;
        
        // Clear existing notifications (except the mark-all-read button)
        const markAllReadBtn = this.notificationContainer.querySelector('button');
        this.notificationContainer.innerHTML = '';
        
        if (markAllReadBtn) {
            this.notificationContainer.appendChild(markAllReadBtn);
        }
        
        // Check if we have notifications
        if (this.notifications.length === 0) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'notification-item text-center py-3';
            emptyMessage.innerText = 'Tidak ada notifikasi baru';
            this.notificationContainer.appendChild(emptyMessage);
            return;
        }
        
        // Add each notification
        this.notifications.forEach(notification => {
            const notificationItem = this.createNotificationElement(notification);
            this.notificationContainer.appendChild(notificationItem);
        });
        
        // Add view all link
        const viewAllLink = document.createElement('a');
        viewAllLink.className = 'dropdown-item text-center text-primary py-2';
        viewAllLink.href = '/notifications.html';
        viewAllLink.innerText = 'Lihat Semua Notifikasi';
        this.notificationContainer.appendChild(viewAllLink);
    }
    
    /**
     * Create a notification element from a notification object
     * @param {Object} notification - The notification object
     * @returns {HTMLElement} - The notification element
     */
    createNotificationElement(notification) {
        const element = document.createElement('div');
        element.className = `notification-item dropdown-item d-flex align-items-start p-2 ${notification.is_read ? 'read' : 'unread'}`;
        element.dataset.id = notification.id;
        
        // Add priority indicator
        let priorityClass = 'bg-info';
        if (notification.priority === 'high') priorityClass = 'bg-warning';
        if (notification.priority === 'urgent') priorityClass = 'bg-danger';
        
        // Set icon based on notification type
        let iconClass = 'fas fa-bell';
        if (notification.type === 'signal_change') iconClass = 'fas fa-chart-line';
        if (notification.type === 'price_alert') iconClass = 'fas fa-tag';
        if (notification.type === 'news') iconClass = 'fas fa-newspaper';
        if (notification.type === 'prediction_update') iconClass = 'fas fa-brain';
        if (notification.type === 'system') iconClass = 'fas fa-cogs';
        
        // Format date
        const date = new Date(notification.created_at);
        const formattedDate = date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        element.innerHTML = `
            <div class="notification-priority-indicator ${priorityClass}" style="width:4px;height:100%;margin-right:8px;"></div>
            <div class="notification-icon me-2">
                <i class="${iconClass}"></i>
            </div>
            <div class="notification-content flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <strong class="notification-title">${notification.title}</strong>
                    <small class="text-muted">${formattedDate}</small>
                </div>
                <p class="notification-message mb-0 small">${notification.message}</p>
                ${notification.ticker ? `<span class="badge bg-light text-dark">${notification.ticker}</span>` : ''}
            </div>
        `;
        
        // Add click event to mark as read
        element.addEventListener('click', (e) => {
            e.preventDefault();
            this.markAsRead(notification.id);
            
            // Handle navigation based on notification type
            if (notification.ticker) {
                window.location.href = `/stock-basic.html?symbol=${notification.ticker}`;
            }
        });
        
        return element;
    }
    
    /**
     * Toggle the notification dropdown
     */
    toggleNotifications() {
        // Mark notifications as read when opened
        if (!this.notificationContainer.classList.contains('show')) {
            // Mark visible notifications as read after a delay
            setTimeout(() => {
                const unreadNotifications = this.notifications.filter(n => !n.is_read);
                unreadNotifications.forEach(n => this.markAsRead(n.id, false));
                // Only update UI after all notifications are marked
                this.updateNotificationBadge();
            }, 2000);
        }
    }
    
    /**
     * Mark a notification as read
     * @param {string} id - The notification ID
     * @param {boolean} updateUI - Whether to update the UI after marking
     */
    async markAsRead(id, updateUI = true) {
        try {
            const response = await fetch(`/api/notifications/mark-read/${id}`, {
                method: 'POST'
            });
            
            if (!response.ok) {
                throw new Error('Failed to mark notification as read');
            }
            
            // Update local notification state
            const notification = this.notifications.find(n => n.id === id);
            if (notification) {
                notification.is_read = true;
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            }
            
            if (updateUI) {
                this.updateNotificationBadge();
                this.renderNotifications();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    /**
     * Mark all notifications as read
     */
    async markAllAsRead() {
        if (!this.userId || this.unreadCount === 0) return;
        
        try {
            const response = await fetch(`/api/notifications/mark-all-read?user_id=${this.userId}`, {
                method: 'POST'
            });
            
            if (!response.ok) {
                throw new Error('Failed to mark all notifications as read');
            }
            
            // Update local state
            this.notifications.forEach(n => n.is_read = true);
            this.unreadCount = 0;
            
            // Update UI
            this.updateNotificationBadge();
            this.renderNotifications();
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }
}

// Initialize notification manager when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.notificationManager = new NotificationManager();
});

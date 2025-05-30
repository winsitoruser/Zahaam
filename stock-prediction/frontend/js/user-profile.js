// user-profile.js - Script untuk halaman profil pengguna

// Data placeholder untuk profil pengguna
let userData = {
    id: 1,
    name: "John Doe",
    email: "john.doe@example.com",
    phone: "+62812345678",
    joinDate: "2024-12-10",
    avatar: "assets/images/default-avatar.svg",
    preferences: {
        notifications: {
            email: true,
            push: true,
            sms: false
        },
        tradingPreferences: {
            defaultTimeframe: "1D",
            defaultChartType: "candlestick",
            defaultIndicators: ["SMA", "RSI"]
        }
    },
    watchlist: [
        { symbol: "BBRI", name: "Bank Rakyat Indonesia", lastPrice: 4580, change: 1.5 },
        { symbol: "BBCA", name: "Bank Central Asia", lastPrice: 9450, change: -0.3 },
        { symbol: "TLKM", name: "Telkom Indonesia", lastPrice: 3750, change: 0.8 }
    ],
    tradingHistory: [
        { date: "2025-05-25", symbol: "BBRI", type: "BUY", price: 4520, shares: 100, value: 452000 },
        { date: "2025-05-27", symbol: "BBRI", type: "SELL", price: 4590, shares: 100, value: 459000, pnl: 7000, pnlPercent: 1.55 },
        { date: "2025-05-28", symbol: "BBCA", type: "BUY", price: 9400, shares: 50, value: 470000 }
    ]
};

// Cek dan load data dari localStorage jika ada
function loadUserData() {
    try {
        const savedData = localStorage.getItem('userData');
        if (savedData) {
            userData = JSON.parse(savedData);
        }
    } catch (e) {
        console.error('Error loading user data:', e);
    }
}

// Fungsi untuk inisialisasi profil pengguna
function initUserProfile() {
    // Data placeholder untuk profil pengguna
    const userData = {
        id: 1,
        name: "John Doe",
        email: "john.doe@example.com",
        phone: "+62812345678",
        joinDate: "2024-12-10",
        avatar: "https://via.placeholder.com/150",
        preferences: {
            notifications: {
                email: true,
                push: true,
                sms: false
            },
            tradingPreferences: {
                defaultTimeframe: "1D",
                defaultChartType: "candlestick",
                defaultIndicators: ["SMA", "RSI"]
            }
        },
        watchlist: [
            { symbol: "BBRI", name: "Bank Rakyat Indonesia", lastPrice: 4580, change: 1.5 },
            { symbol: "BBCA", name: "Bank Central Asia", lastPrice: 9450, change: -0.3 },
            { symbol: "TLKM", name: "Telkom Indonesia", lastPrice: 3750, change: 0.8 }
        ],
        tradingHistory: [
            { date: "2025-05-25", symbol: "BBRI", type: "BUY", price: 4520, shares: 100, value: 452000 },
            { date: "2025-05-27", symbol: "BBRI", type: "SELL", price: 4590, shares: 100, value: 459000, pnl: 7000, pnlPercent: 1.55 },
            { date: "2025-05-28", symbol: "BBCA", type: "BUY", price: 9400, shares: 50, value: 470000 }
        ]
    };

    // Inisialisasi tab profil
    const initProfileTabs = () => {
        const tabButtons = document.querySelectorAll('.profile-tab-btn');
        const tabContents = document.querySelectorAll('.profile-tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('show', 'active'));
                
                // Add active class to clicked button and corresponding content
                button.classList.add('active');
                const tabId = button.getAttribute('data-bs-target');
                document.querySelector(tabId).classList.add('show', 'active');
            });
        });
    };

    // Render profil pengguna
    const renderUserProfile = () => {
        // Update profile photo
        const profilePhoto = document.getElementById('profilePhoto');
        if (profilePhoto) {
            profilePhoto.src = userData.avatar || 'assets/images/default-avatar.svg';
        }
        
        // Update profile name and details
        const profileName = document.getElementById('profileName');
        const profileEmail = document.getElementById('profileEmail');
        const detailName = document.getElementById('detailName');
        const detailEmail = document.getElementById('detailEmail');
        const detailPhone = document.getElementById('detailPhone');
        const detailJoinDate = document.getElementById('detailJoinDate');
        
        if (profileName) profileName.textContent = userData.name;
        if (profileEmail) profileEmail.textContent = userData.email;
        if (detailName) detailName.textContent = userData.name;
        if (detailEmail) detailEmail.textContent = userData.email;
        if (detailPhone) detailPhone.textContent = userData.phone;
        if (detailJoinDate) detailJoinDate.textContent = userData.joinDate;
        // Update nama pengguna (di atas foto profil)
        const profilePhotoEl = document.getElementById('profilePhoto');
        if (profilePhotoEl) {
            profilePhotoEl.setAttribute('alt', userData.name);
        }
        
        // Render informasi dasar
        const profileInfoEl = document.getElementById('profileInfo');
        if (profileInfoEl) {
            profileInfoEl.innerHTML = `
                <div class="text-center mb-4">
                    <h4 class="mb-2">${userData.name}</h4>
                    <p class="text-muted">Member since ${new Date(userData.joinDate).toLocaleDateString()}</p>
                </div>
                
                <div class="list-group mb-4">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-envelope text-primary me-2"></i>
                            <strong>Email</strong>
                        </div>
                        <span>${userData.email}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-phone text-primary me-2"></i>
                            <strong>Phone</strong>
                        </div>
                        <span>${userData.phone}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-shield-check text-primary me-2"></i>
                            <strong>Account Status</strong>
                        </div>
                        <span class="badge bg-success">Premium</span>
                    </div>
                </div>
            `;
        }
        
        // Render watchlist
        const watchlistEl = document.getElementById('watchlistTable');
        if (watchlistEl) {
            let watchlistHtml = '';
            
            userData.watchlist.forEach(stock => {
                const changeClass = stock.change >= 0 ? 'text-success' : 'text-danger';
                const changeIcon = stock.change >= 0 ? 'bi-arrow-up' : 'bi-arrow-down';
                
                watchlistHtml += `
                    <tr>
                        <td>
                            <a href="stock-basic.html?symbol=${stock.symbol}" class="text-decoration-none">
                                ${stock.symbol}
                            </a>
                        </td>
                        <td>${stock.name}</td>
                        <td class="text-end">Rp ${stock.lastPrice.toLocaleString()}</td>
                        <td class="${changeClass} text-end">
                            <i class="bi ${changeIcon}"></i> ${Math.abs(stock.change)}%
                        </td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-danger remove-watchlist" data-symbol="${stock.symbol}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            
            watchlistEl.innerHTML = watchlistHtml;
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-watchlist').forEach(button => {
                button.addEventListener('click', () => {
                    const symbol = button.getAttribute('data-symbol');
                    // In a real app, this would make an API call to remove the stock
                    alert(`Removed ${symbol} from watchlist`);
                    // Then refresh the watchlist
                });
            });
        }
        
        // Render trading history
        const historyEl = document.getElementById('tradingHistoryTable');
        if (historyEl) {
            let historyHtml = '';
            
            userData.tradingHistory.forEach(trade => {
                const typeClass = trade.type === 'BUY' ? 'bg-success' : 'bg-danger';
                
                historyHtml += `
                    <tr>
                        <td>${trade.date}</td>
                        <td>
                            <a href="stock-basic.html?symbol=${trade.symbol}" class="text-decoration-none">
                                ${trade.symbol}
                            </a>
                        </td>
                        <td>
                            <span class="badge ${typeClass}">${trade.type}</span>
                        </td>
                        <td class="text-end">Rp ${trade.price.toLocaleString()}</td>
                        <td class="text-end">${trade.shares}</td>
                        <td class="text-end">Rp ${trade.value.toLocaleString()}</td>
                        <td class="text-end">
                            ${trade.pnl ? `<span class="${trade.pnl >= 0 ? 'text-success' : 'text-danger'}">
                                Rp ${trade.pnl.toLocaleString()} (${trade.pnlPercent.toFixed(2)}%)
                            </span>` : '-'}
                        </td>
                    </tr>
                `;
            });
            
            historyEl.innerHTML = historyHtml;
        }
        
        // Render notification preferences
        const notifForm = document.getElementById('notificationForm');
        if (notifForm) {
            const emailCheck = notifForm.querySelector('#emailNotif');
            const pushCheck = notifForm.querySelector('#pushNotif');
            const smsCheck = notifForm.querySelector('#smsNotif');
            
            if (emailCheck) emailCheck.checked = userData.preferences.notifications.email;
            if (pushCheck) pushCheck.checked = userData.preferences.notifications.push;
            if (smsCheck) smsCheck.checked = userData.preferences.notifications.sms;
            
            notifForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // In a real app, this would make an API call to save preferences
                userData.preferences.notifications = {
                    email: emailCheck.checked,
                    push: pushCheck.checked,
                    sms: smsCheck.checked
                };
                
                alert('Notification preferences saved!');
            });
        }
        
        // Render trading preferences
        const tradingForm = document.getElementById('tradingPreferencesForm');
        if (tradingForm) {
            const timeframeSelect = tradingForm.querySelector('#defaultTimeframe');
            const chartTypeSelect = tradingForm.querySelector('#defaultChartType');
            
            if (timeframeSelect) timeframeSelect.value = userData.preferences.tradingPreferences.defaultTimeframe;
            if (chartTypeSelect) chartTypeSelect.value = userData.preferences.tradingPreferences.defaultChartType;
            
            // Set default indicators
            userData.preferences.tradingPreferences.defaultIndicators.forEach(indicator => {
                const checkbox = tradingForm.querySelector(`#${indicator.toLowerCase()}Indicator`);
                if (checkbox) checkbox.checked = true;
            });
            
            tradingForm.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Get selected indicators
                const selectedIndicators = [];
                tradingForm.querySelectorAll('.indicator-checkbox:checked').forEach(checkbox => {
                    selectedIndicators.push(checkbox.value);
                });
                
                // In a real app, this would make an API call to save preferences
                userData.preferences.tradingPreferences = {
                    defaultTimeframe: timeframeSelect.value,
                    defaultChartType: chartTypeSelect.value,
                    defaultIndicators: selectedIndicators
                };
                
                alert('Trading preferences saved!');
            });
        }
    };
    
    // Render trading history
    const historyEl = document.getElementById('tradingHistoryTable');
    if (historyEl) {
        let historyHtml = '';
        
        userData.tradingHistory.forEach(trade => {
            const typeClass = trade.type === 'BUY' ? 'bg-success' : 'bg-danger';
            
            historyHtml += `
                <tr>
                    <td>${trade.date}</td>
                    <td>
                        <a href="stock-basic.html?symbol=${trade.symbol}" class="text-decoration-none">
                            ${trade.symbol}
                        </a>
                    </td>
                    <td>
                        <span class="badge ${typeClass}">${trade.type}</span>
                    </td>
                    <td class="text-end">Rp ${trade.price.toLocaleString()}</td>
                    <td class="text-end">${trade.shares}</td>
                    <td class="text-end">Rp ${trade.value.toLocaleString()}</td>
                    <td class="text-end">
                        ${trade.pnl ? `<span class="${trade.pnl >= 0 ? 'text-success' : 'text-danger'}">
                            Rp ${trade.pnl.toLocaleString()} (${trade.pnlPercent.toFixed(2)}%)
                        </span>` : '-'}
                    </td>
                </tr>
            `;
        });
        
        historyEl.innerHTML = historyHtml;
    }
    
    // Render notification preferences
    const notifForm = document.getElementById('notificationForm');
    if (notifForm) {
        const emailCheck = notifForm.querySelector('#emailNotif');
        const pushCheck = notifForm.querySelector('#pushNotif');
        const smsCheck = notifForm.querySelector('#smsNotif');
        
        if (emailCheck) emailCheck.checked = userData.preferences.notifications.email;
        if (pushCheck) pushCheck.checked = userData.preferences.notifications.push;
        if (smsCheck) smsCheck.checked = userData.preferences.notifications.sms;
        
        notifForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // In a real app, this would make an API call to save preferences
            userData.preferences.notifications = {
                email: emailCheck.checked,
                push: pushCheck.checked,
                sms: smsCheck.checked
            };
            
            alert('Notification preferences saved!');
        });
    }
    
    // Render trading preferences
    const tradingForm = document.getElementById('tradingPreferencesForm');
    if (tradingForm) {
        const timeframeSelect = tradingForm.querySelector('#defaultTimeframe');
        const chartTypeSelect = tradingForm.querySelector('#defaultChartType');
        
        if (timeframeSelect) timeframeSelect.value = userData.preferences.tradingPreferences.defaultTimeframe;
        if (chartTypeSelect) chartTypeSelect.value = userData.preferences.tradingPreferences.defaultChartType;
        
        // Set default indicators
        userData.preferences.tradingPreferences.defaultIndicators.forEach(indicator => {
            const checkbox = tradingForm.querySelector(`#${indicator.toLowerCase()}Indicator`);
            if (checkbox) checkbox.checked = true;
        });
        
        tradingForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            // Get selected indicators
            const selectedIndicators = [];
            tradingForm.querySelectorAll('.indicator-checkbox:checked').forEach(checkbox => {
                selectedIndicators.push(checkbox.value);
            });
            
            // In a real app, this would make an API call to save preferences

// Inisialisasi fungsi edit profil
function initProfileEdit() {
    const editModal = document.getElementById('editProfileModal');
    const editNameInput = document.getElementById('editName');
    const editEmailInput = document.getElementById('editEmail');
    const editPhoneInput = document.getElementById('editPhone');
    const saveBtn = document.getElementById('saveProfileChanges');
    
    // Mengisi data awal ke form edit
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function () {
            if (editNameInput) editNameInput.value = userData.name || '';
            if (editEmailInput) editEmailInput.value = userData.email || '';
            if (editPhoneInput) editPhoneInput.value = userData.phone || '';
        });
    }
    
    // Menangani simpan perubahan
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            // Validasi form sederhana
            if (!editNameInput.value.trim()) {
                alert('Nama tidak boleh kosong');
                return;
            }
            
            if (!editEmailInput.value.trim() || !isValidEmail(editEmailInput.value)) {
                alert('Email tidak valid');
                return;
            }
            
            // Simpan perubahan ke data pengguna
            const oldName = userData.name;
            userData.name = editNameInput.value.trim();
            userData.email = editEmailInput.value.trim();
            userData.phone = editPhoneInput.value.trim();
            
            // Simpan ke localStorage untuk demo
            saveUserData();
            
            // Perbarui tampilan
            renderUserProfile();
            
            // Tutup modal
            const modalInstance = bootstrap.Modal.getInstance(editModal);
            if (modalInstance) {
                modalInstance.hide();
            }
            
            // Tampilkan notifikasi
            showToast('Sukses', 'Profil berhasil diperbarui', 'success');
            
            // Log perubahan
            console.log(`Nama diubah dari "${oldName}" menjadi "${userData.name}"`);
        });
    }
    
    /**
     * Validasi format email sederhana
     */
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    /**
     * Simpan data pengguna ke localStorage
     */
    function saveUserData() {
        try {
            localStorage.setItem('userData', JSON.stringify(userData));
        } catch (e) {
            console.error('Gagal menyimpan data pengguna:', e);
        }
    }
    
    /**
     * Tampilkan toast notifikasi
     */
    function showToast(title, message, type = 'info') {
        // Check if Bootstrap toast component is available
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            // Create toast element
            const toastEl = document.createElement('div');
            toastEl.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'primary'} border-0`;
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            
            // Toast content
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong>: ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            // Add to container or body
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.appendChild(toastEl);
            
            // Initialize and show toast
            const toast = new bootstrap.Toast(toastEl, {
                delay: 3000,
                autohide: true
            });
            
            toast.show();
            
            // Remove toast element after hiding
            toastEl.addEventListener('hidden.bs.toast', function() {
                toastEl.remove();
            });
        } else {
            // Fallback to alert if Bootstrap is not available
            alert(`${title}: ${message}`);
        }
    }
}

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
    loadUserData();
    initUserProfile();
});

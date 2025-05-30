/**
 * Profile Photo Upload Handler
 * Handles profile photo preview and upload functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const photoUploadInput = document.getElementById('photoUpload');
    const profilePhotoImg = document.getElementById('profilePhoto');
    
    // Listen for file selection
    if (photoUploadInput) {
        photoUploadInput.addEventListener('change', handlePhotoUpload);
    }
    
    // Check for stored profile photo in localStorage
    const storedPhoto = localStorage.getItem('userProfilePhoto');
    if (storedPhoto && profilePhotoImg) {
        profilePhotoImg.src = storedPhoto;
    }
    
    /**
     * Handle photo upload process
     * @param {Event} event - The change event from file input
     */
    function handlePhotoUpload(event) {
        const file = event.target.files[0];
        
        if (!file) return;
        
        // Validate file type
        if (!file.type.match('image.*')) {
            showToast('Kesalahan', 'File harus berupa gambar (JPG, PNG, GIF)', 'error');
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showToast('Kesalahan', 'Ukuran gambar maksimal 5MB', 'error');
            return;
        }
        
        // Create FileReader to read and preview the image
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Update profile photo preview
            if (profilePhotoImg) {
                profilePhotoImg.src = e.target.result;
                
                // Store in localStorage for demo purposes
                // In production, this would be uploaded to a server
                localStorage.setItem('userProfilePhoto', e.target.result);
                
                // Show success message
                showToast('Sukses', 'Foto profil berhasil diperbarui', 'success');
            }
        };
        
        // Read the image file as a data URL
        reader.readAsDataURL(file);
        
        // In a real application, you would upload the file to a server here
        // simulateUploadToServer(file);
    }
    
    /**
     * Display toast notification
     * @param {string} title - Toast title
     * @param {string} message - Toast message
     * @param {string} type - Toast type (success, error, warning, info)
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
    
    /**
     * Simulate uploading file to server (for demonstration)
     * @param {File} file - The file to upload
     */
    function simulateUploadToServer(file) {
        console.log('Uploading file to server:', file.name);
        
        // Create FormData object
        const formData = new FormData();
        formData.append('profilePhoto', file);
        
        // Simulate API request with delay
        setTimeout(() => {
            console.log('Upload complete:', file.name);
            // In a real application, this would be an actual fetch/XHR request to your backend
            /*
            fetch('/api/user/profile-photo', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
                showToast('Sukses', 'Foto profil berhasil diupload ke server', 'success');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Kesalahan', 'Gagal mengupload foto ke server', 'error');
            });
            */
        }, 1500);
    }
});

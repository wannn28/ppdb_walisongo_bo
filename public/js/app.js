function showLoading() {
    const loading = document.getElementById('global-loading');
    if (loading) loading.classList.remove('hidden');
}

function hideLoading() {
    const loading = document.getElementById('global-loading');
    if (loading) loading.classList.add('hidden');
}

async function AwaitFetchApi(url, method, data, skipAuth = false) {
    const token = localStorage.getItem('token');
    const BASE_URL = window.API_BASE_URL 
    if (!skipAuth && !token) {
        print.warn("Token tidak ditemukan di localStorage.");
        window.location.href = '/login';
        return Promise.resolve({ message: 'Token tidak ditemukan' });
    }

    const isFormData = data instanceof FormData;

    const headers = {
        'Accept': 'application/json',
        ...(isFormData ? {} : { 'Content-Type': 'application/json' })
    };

    if (!skipAuth) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const options = {
        method: method,
        headers: headers
    };

    if (method !== 'GET' && method !== 'HEAD') {
        options.body = isFormData ? data : JSON.stringify(data);
    }

    showLoading();

    let timeoutReached = false;
    const timeout = setTimeout(() => {
        timeoutReached = true;
        hideLoading();
        showNotification("Permintaan melebihi waktu tunggu. Silakan coba lagi.", "error");
    }, 30000);

    try {
        const response = await fetch(BASE_URL + url, options);
        clearTimeout(timeout);
        if (timeoutReached) return { message: 'Timeout' };
        hideLoading();

        const result = await response.json();
        print.log("Fetch result:", result);

        // Hanya tampilkan notifikasi untuk method non-GET
        if (['POST', 'PUT', 'DELETE'].includes(method) && result.meta?.message) {
            if (response.ok) {
                showNotification(result.meta.message, 'success');
            }
        }

        if (!response.ok) {
            if (response.status === 422 && result.errors) {
                // Gabungkan semua error jadi satu string
                const allErrors = Object.values(result.errors)
                    .flat()
                    .join('<br>');
        
                showNotification(allErrors, 'error'); // bisa juga pakai modal atau toast HTML
            } else if (response.status === 401 && !skipAuth) {
                print.error('Unauthenticated. Redirecting to login...');
                showNotification("Sesi Anda telah berakhir. Silakan login kembali.", "error");
                window.location.href = '/login';
            } else if (result.meta?.message) {
                showNotification(result.meta.message, 'info');
            }
        }

        return result;
    } catch (error) {
        clearTimeout(timeout);
        hideLoading();
        if (!timeoutReached && ['POST', 'PUT'].includes(method)) {
            showNotification("Terjadi kesalahan jaringan", "error");
        }
        print.error("Fetch error:", error);
        return { message: 'Fetch failed', error };
    }
}

function showNotification(message, type = 'success') {
    Swal.fire({
        text: message,
        icon: type,
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
}

// Tambahkan kode ini di bagian atas file untuk mengecek notifikasi yang pending
document.addEventListener('DOMContentLoaded', () => {
    const pendingNotification = localStorage.getItem('pendingNotification');
    if (pendingNotification) {
        const { message, type } = JSON.parse(pendingNotification);
        showNotification(message, type);
    }
});


/**
 * Fungsi untuk menampilkan konfirmasi delete dengan SweetAlert2
 * @param {string} message - Pesan konfirmasi
 * @returns {Promise} - Promise yang akan resolve dengan true jika user mengkonfirmasi
 */
function showDeleteConfirmation(message = 'Apakah Anda yakin ingin menghapus data ini?', confirmButtonText = 'Ya, Hapus!', cancelButtonText = 'Batal') {
    return Swal.fire({
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText,
        cancelButtonText: 'Batal',
        customClass: {
            container: 'swal-z-top'
        }
    });
}


/**
 * Fungsi untuk membuka modal berdasarkan ID
 * @param {string} modalId - ID dari modal yang akan dibuka
 * @param {Function} callback - Callback yang akan dijalankan setelah modal dibuka (opsional)
 */
function openModal(modalId, callback = null) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');

        // Set z-index to be very high
        modal.style.zIndex = '9999999';
        
        // Execute callback if provided
        if (typeof callback === 'function') {
            callback();
        }
    } else {
        print.error(`Modal dengan ID ${modalId} tidak ditemukan`);
    }
}

/**
 * Fungsi untuk menutup modal berdasarkan ID
 * @param {string} modalId - ID dari modal yang akan ditutup
 * @param {Function} callback - Callback yang akan dijalankan setelah modal ditutup (opsional)
 */
function closeModal(modalId, callback = null) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        
        // Reset form jika ada di dalam modal
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
        
        // Execute callback if provided
        if (typeof callback === 'function') {
            callback();
        }
    } else {
        print.error(`Modal dengan ID ${modalId} tidak ditemukan`);
    }
}

/**
 * Fungsi untuk menginisialisasi event listener tutup untuk semua modal
 * Menambahkan event listener untuk tombol dengan attribute data-close-modal
 */
function initializeModalClosers() {
    document.querySelectorAll('[data-close-modal]').forEach(button => {
        const modalId = button.getAttribute('data-close-modal');
        button.addEventListener('click', () => closeModal(modalId));
    });

    // Close modal when clicking outside (optional)
    document.addEventListener('click', (e) => {
        document.querySelectorAll('.modal-container').forEach(modal => {
            if (e.target === modal) {
                closeModal(modal.id);
            }
        });
    });
}

// Inisialisasi event listener modal saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initializeModalClosers();
    
    // Jika perlu inisialisasi lainnya tambahkan di sini
});

// Utility untuk membuat preview file
function previewFile(url, previewModalId = 'previewModal', previewFrameId = 'filePreview') {
    const modal = document.getElementById(previewModalId);
    const preview = document.getElementById(previewFrameId);
    
    if (modal && preview) {
        preview.src = url;
        openModal(previewModalId);
    } else {
        print.error('Modal preview atau frame tidak ditemukan');
    }
}

/**
 * Fungsi untuk memformat tanggal ke format Indonesia
 * @param {string|Date} dateString - Tanggal yang akan diformat
 * @param {boolean} withTime - Apakah akan menyertakan waktu
 * @returns {string} - Tanggal yang sudah diformat
 */
function formatDate(dateString, withTime = false) {
    if (!dateString) return '-';
    
    const date = new Date(dateString);
    
    // Cek apakah date valid
    if (isNaN(date.getTime())) return '-';
    
    // Format tanggal Indonesia: dd MMM yyyy
    const formattedDate = date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    
    // Jika withTime true, tambahkan waktu
    if (withTime) {
        const formattedTime = date.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
        return `${formattedDate} ${formattedTime}`;
    }
    
    return formattedDate;
}

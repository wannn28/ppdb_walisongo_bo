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
            showNotification(result.meta.message, response.ok ? 'success' : 'error');
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
                showNotification(result.meta.message, 'error');
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
        icon: type === 'success' ? 'success' : 'error',
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
 * Fungsi untuk menampilkan alert dengan SweetAlert2
 * @param {string} message - Pesan yang akan ditampilkan
 * @param {string} type - Tipe alert ('success', 'error', 'warning', 'info')
 */
function showAlert(message, type = 'success') {
    Swal.fire({
        text: message,
        icon: type,
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
}

/**
 * Fungsi untuk menampilkan konfirmasi delete dengan SweetAlert2
 * @param {string} message - Pesan konfirmasi
 * @returns {Promise} - Promise yang akan resolve dengan true jika user mengkonfirmasi
 */
function showDeleteConfirmation(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return Swal.fire({
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
}

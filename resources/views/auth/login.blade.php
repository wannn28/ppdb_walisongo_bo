@extends('layouts.guest')

@section('guest')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Login Admin
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Silakan masuk dengan nomor telepon Anda
            </p>
        </div>
        <div class="mt-8 space-y-6">
            <div class="rounded-md shadow-sm">
                <div>
                    <label for="phoneInput" class="sr-only">Nomor Telepon</label>
                    <input id="phoneInput" name="no_telp" type="tel" required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                           placeholder="Masukkan nomor telepon">
                </div>
            </div>

            <div>
                <button id="loginBtn" type="button"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    Masuk
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('loginBtn');
    const phoneInput = document.getElementById('phoneInput');

    // Kosongkan token saat membuka halaman login
    localStorage.setItem('token', '');

    loginBtn.addEventListener('click', async () => {
        const no_telp = phoneInput.value;

        if (!no_telp) {
            showNotification("Nomor HP wajib diisi!", "error");
            return;
        }

        try {
            const response = await AwaitFetchApi('auth/login', 'POST', { no_telp }, true);
            if (response && response.meta?.code === 200) {
                localStorage.setItem('token', response.data.token);
                window.location.href = '/';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification("Terjadi kesalahan saat login", "error");
        }
    });

    // Tambahkan event listener untuk enter key
    phoneInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            loginBtn.click();
        }
    });
});
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-6">Homepage Management</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Banner Utama</h2>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Banner Aktif</label>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="banner-container">
                <!-- Banner akan di-render secara dinamis menggunakan JavaScript -->
            </div>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Banner Baru</label>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="urutan">
                        Urutan
                    </label>
                    <input type="number" id="urutan" name="urutan" min="1" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="border-dashed border-2 border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" id="banner-upload" name="image" class="hidden" accept="image/*">
                    <label for="banner-upload" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                        <p class="mt-2 text-sm text-gray-500">Klik untuk upload banner baru</p>
                    </label>
                    <div id="preview" class="mt-2 hidden">
                        <img id="imagePreview" class="mx-auto max-h-32 object-contain">
                    </div>
                </div>
            </form>
        </div>
        
        <button onclick="uploadBanner()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
            Simpan Perubahan
        </button>
    </div>
</div>

<script>
// Fungsi untuk memuat semua banner saat halaman dimuat
async function loadBanners() {
    try {
        const response = await AwaitFetchApi('admin/homepage', 'GET');
        if (response.meta?.code === 200) {
            renderBanners(response.data.data || []);
        } else {
            alert('Gagal memuat banner: ' + response.meta?.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat banner');
    }
}

// Fungsi untuk merender banner ke dalam container
function renderBanners(banners) {
    const container = document.getElementById('banner-container');
    container.innerHTML = '';
    
    banners.forEach(banner => {
        const bannerElement = document.createElement('div');
        bannerElement.className = 'border rounded-lg p-2 relative';
        bannerElement.innerHTML = `
            <img src="${banner.url}" alt="Banner ${banner.urutan}" class="w-full h-32 object-cover rounded">
            <div class="mt-2 flex justify-between items-center">
                <span class="text-sm">Urutan: ${banner.urutan}</span>
                <button class="text-red-500 hover:text-red-700" onclick="deleteBanner(${banner.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(bannerElement);
    });
}

// Preview gambar sebelum upload
document.getElementById('banner-upload').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('preview').classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
});

// Fungsi untuk upload banner
async function uploadBanner() {
    const formData = new FormData(document.getElementById('uploadForm'));
    const fileInput = document.getElementById('banner-upload');
    
    if (!fileInput.files[0]) {
        alert('Pilih gambar terlebih dahulu');
        return;
    }
    
    if (!formData.get('urutan')) {
        alert('Masukkan urutan banner');
        return;
    }

    try {
        const response = await AwaitFetchApi('admin/homepage', 'POST', formData);
        if (response.meta?.code === 200) {
            alert(response.meta.message || 'Banner berhasil diupload');
            document.getElementById('uploadForm').reset();
            document.getElementById('preview').classList.add('hidden');
            loadBanners(); // Muat ulang daftar banner
        } else {
            alert('Gagal upload banner: ' + (response.meta?.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat upload banner');
    }
}

// Fungsi untuk menghapus banner
async function deleteBanner(id) {
    const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus banner ini?');
    
    if (!result.isConfirmed) {
        return;
    }

    try {
        const response = await AwaitFetchApi(`homepage/${id}`, 'DELETE');
        if (response.meta?.code === 200) {
            showAlert(response.meta.message || 'Banner berhasil dihapus', 'success');
            loadBanners(); // Muat ulang daftar banner
        } else {
            showAlert(response.meta?.message || 'Gagal menghapus banner', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus banner', 'error');
    }
}

// Muat banner saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadBanners);
</script>
@endsection
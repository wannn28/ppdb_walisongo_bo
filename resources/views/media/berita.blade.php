@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Berita</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Berita Aktif</h2>
        
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="berita-container">
                <!-- Berita akan di-render secara dinamis menggunakan JavaScript -->
            </div>
        </div>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Tambah Berita Baru</h2>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="urutan">
                        Urutan
                    </label>
                    <input type="number" id="urutan" name="urutan" min="1" 
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="jenjang">
                        Jenjang Sekolah
                    </label>
                    <select id="jenjang" name="jenjang_sekolah" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                    </select>
                </div>
                
                <div class="border-dashed border-2 border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" id="berita-upload" name="image" class="hidden" accept="image/*">
                    <label for="berita-upload" class="cursor-pointer">
                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                        <p class="mt-2 text-sm text-gray-500">Klik untuk upload berita baru</p>
                    </label>
                    <div id="preview" class="mt-2 hidden">
                        <img id="imagePreview" class="mx-auto max-h-32 object-contain">
                    </div>
                </div>
            </form>
            
            <div class="mt-4">
                <button onclick="uploadBerita()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Simpan Berita
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk memuat semua berita saat halaman dimuat
async function loadBerita() {
    try {
        const response = await AwaitFetchApi('admin/berita', 'GET');
        if (response.meta?.code === 200) {
            renderBerita(response.data.data || []);
        } else {
            alert('Gagal memuat berita: ' + response.meta?.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat berita');
    }
}

// Fungsi untuk merender berita ke dalam container
function renderBerita(beritas) {
    const container = document.getElementById('berita-container');
    container.innerHTML = '';
    
    beritas.forEach(berita => {
        const beritaElement = document.createElement('div');
        beritaElement.className = 'border rounded-lg p-2 relative';
        beritaElement.innerHTML = `
            <img src="${berita.url}" alt="Berita ${berita.urutan}" class="w-full h-40 object-cover rounded">
            <div class="mt-2">
                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">${berita.jenjang_sekolah}</span>
                <p class="text-sm mt-1">Urutan: ${berita.urutan}</p>
                <p class="text-xs text-gray-500">ID: ${berita.id}</p>
            </div>
            <div class="mt-2 flex justify-between items-center">
                <span class="text-sm text-gray-500">${new Date(berita.created_at).toLocaleDateString('id-ID')}</span>
                <div>
                    <button class="text-red-500 hover:text-red-700" onclick="deleteBerita(${berita.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(beritaElement);
    });
}

// Preview gambar sebelum upload
document.getElementById('berita-upload').addEventListener('change', function(e) {
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

// Fungsi untuk upload berita
async function uploadBerita() {
    const formData = new FormData(document.getElementById('uploadForm'));
    const fileInput = document.getElementById('berita-upload');
    
    if (!fileInput.files[0]) {
        alert('Pilih gambar terlebih dahulu');
        return;
    }
    
    if (!formData.get('urutan')) {
        alert('Masukkan urutan berita');
        return;
    }

    try {
        const response = await AwaitFetchApi('admin/berita', 'POST', formData);
        if (response.meta?.code === 200) {
            alert(response.meta.message || 'Berita berhasil diupload');
            document.getElementById('uploadForm').reset();
            document.getElementById('preview').classList.add('hidden');
            loadBerita(); // Muat ulang daftar berita
        } else {
            alert('Gagal upload berita: ' + (response.meta?.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat upload berita');
    }
}

async function deleteBerita(id) {
    const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus berita ini?');
    
    if (!result.isConfirmed) {
        return;
    }

    try {
        const response = await AwaitFetchApi(`admin/berita/${id}`, 'DELETE');
        if (response.meta?.code === 200) {
            showAlert(response.meta.message || 'Berita berhasil dihapus', 'success');
            loadBerita(); // Muat ulang daftar berita
        } else {
            showAlert(response.meta?.message || 'Gagal menghapus berita', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan saat menghapus berita', 'error');
    }
}

// Fungsi untuk edit berita
async function editBerita(id) {
    try {
        const response = await AwaitFetchApi(`admin/berita/${id}`, 'GET');
        if (response.meta?.code === 200) {
            const berita = response.data;
            document.getElementById('urutan').value = berita.urutan;
            document.getElementById('jenjang').value = berita.jenjang_sekolah;
            // Tampilkan gambar preview jika ada
            if (berita.url) {
                document.getElementById('imagePreview').src = berita.url;
                document.getElementById('preview').classList.remove('hidden');
            }
            // Tambahkan ID berita ke form untuk keperluan update
            const form = document.getElementById('uploadForm');
            form.dataset.editId = id;
            // Ubah teks tombol
            document.querySelector('button[onclick="uploadBerita()"]').textContent = 'Update Berita';
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengambil data berita');
    }
}

// Muat berita saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadBerita);
</script>
@endsection
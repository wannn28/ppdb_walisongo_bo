@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Jadwal</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Jadwal Kegiatan PPDB</h2>
        
        <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="jadwal-container">
                <!-- Jadwal akan di-render secara dinamis menggunakan JavaScript -->
            </div>
        </div>
        
        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-4">Tambah Jadwal Baru</h2>
            <div class="border-dashed border-2 border-gray-300 rounded-lg p-6 text-center cursor-pointer" onclick="openModal('jadwalModal')">
                <i class="fas fa-plus-circle text-3xl text-gray-400"></i>
                <p class="mt-2 text-sm text-gray-500">Klik untuk menambah jadwal baru</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Jadwal -->
<div id="jadwalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Tambah Jadwal</h3>
            <form id="jadwalForm" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="jenjang">
                        Jenjang Sekolah
                    </label>
                    <select id="jenjang" name="jenjang_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="SD">SD</option>
                        <option value="SMP 1">SMP 1</option>
                        <option value="SMP 2">SMP 2</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
                        Upload Gambar
                    </label>
                    <div class="border-dashed border-2 border-gray-300 rounded-lg p-4 text-center">
                        <input type="file" id="image" name="image" accept="image/*" class="hidden">
                        <label for="image" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                            <p class="mt-2 text-sm text-gray-500">Klik untuk upload gambar</p>
                        </label>
                        <div id="preview" class="mt-2 hidden">
                            <img id="imagePreview" class="mx-auto max-h-32 object-contain">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="jadwalModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div id="imageViewModal" class="fixed inset-0 bg-gray-900 bg-opacity-80 hidden overflow-y-auto h-full w-full modal-container">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="relative max-w-4xl w-full">
            <button data-close-modal="imageViewModal" class="absolute top-0 right-0 -mt-12 -mr-12 text-white text-2xl hover:text-gray-300 z-50">
                <i class="fas fa-times"></i>
            </button>
            <img id="fullSizeImage" class="w-full object-contain max-h-[80vh]" src="" alt="Jadwal Preview">
        </div>
    </div>
</div>

<script>
// Fungsi untuk memuat semua jadwal saat halaman dimuat
async function loadJadwal() {
    try {
        const response = await AwaitFetchApi('admin/media', 'GET');
        print.log('Response:', response); // Tambahkan log untuk debugging
        if (response.meta?.code === 200) {
            renderJadwal(response.data || []); // Ubah dari response.data.data menjadi response.data
        } else {
            showNotification(response.meta?.message || 'Gagal memuat jadwal');
        }
    } catch (error) {
        print.error('Error:', error);
        showNotification('Terjadi kesalahan saat memuat jadwal', 'error');
    }
}

// Fungsi untuk menampilkan gambar dalam ukuran besar
function viewFullImage(url) {
    document.getElementById('fullSizeImage').src = url;
    openModal('imageViewModal');
}

// Fungsi untuk merender jadwal ke dalam container
function renderJadwal(jadwals) {
    const container = document.getElementById('jadwal-container');
    container.innerHTML = '';
    
    if (!Array.isArray(jadwals)) {
        print.error('Data jadwal bukan array');
        return;
    }
    
    const filteredJadwals = jadwals.filter(jadwal => jadwal.nama === 'jadwal');
    
    if (filteredJadwals.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center text-gray-500 py-8">
                Belum ada jadwal yang ditambahkan
            </div>
        `;
        return;
    }
    
    filteredJadwals.forEach(jadwal => {
        const jadwalElement = document.createElement('div');
        jadwalElement.className = 'border rounded-lg p-4 relative';
        jadwalElement.innerHTML = `
            <img src="${jadwal.url}" alt="Jadwal ${jadwal.id}" 
                class="w-full h-40 object-cover rounded mb-3 cursor-pointer hover:opacity-90 transition-opacity" 
                onclick="viewFullImage('${jadwal.url}')"
                title="Klik untuk memperbesar">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">${jadwal.jenjang_sekolah}</span>
                    <p class="text-xs text-gray-500 mt-1">ID: ${jadwal.id}</p>
                    <p class="text-xs text-gray-500">Public ID: ${jadwal.public_id}</p>
                </div>
            </div>
            <div class="flex justify-end gap-2">
                <button class="text-red-500 hover:text-red-700" onclick="deleteJadwal(${jadwal.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(jadwalElement);
    });
}

// Preview gambar sebelum upload
document.getElementById('image').addEventListener('change', function(e) {
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

async function editJadwal(id) {
    try {
        const response = await AwaitFetchApi(`admin/media/${id}`, 'GET');
        if (response.meta?.code === 200) {
            const jadwal = response.data;
            document.getElementById('jenjang').value = jadwal.jenjang_sekolah;
            if (jadwal.url) {
                document.getElementById('imagePreview').src = jadwal.url;
                document.getElementById('preview').classList.remove('hidden');
            }
            document.getElementById('jadwalForm').setAttribute('data-id', id);
            document.getElementById('modalTitle').textContent = 'Edit Jadwal';
            openModal('jadwalModal');
        }
    } catch (error) {
        showNotification(response.meta?.message || 'Gagal mengambil data jadwal', 'error');
    }
}

async function deleteJadwal(id) {
    const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus jadwal ini?');
    
    if (!result.isConfirmed) {
        return;
    }

    try {
        const response = await AwaitFetchApi(`admin/media/${id}`, 'DELETE');
        if (response.meta?.code === 200) {
            showNotification(response.meta.message || 'Jadwal berhasil dihapus', 'success');
            loadJadwal(); // Muat ulang daftar jadwal
        } else {
            showNotification(response.meta?.message || 'Gagal menghapus jadwal', 'error');
        }
    } catch (error) {
        print.error('Error:', error);
        showNotification('Terjadi kesalahan saat menghapus jadwal', 'error');
    }
}

document.getElementById('jadwalForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = this.getAttribute('data-id');
    const endpoint = id ? `admin/media/${id}` : 'admin/media/jadwal';
    const method = id ? 'PUT' : 'POST';

    if (!formData.get('image') && !id) {
        showNotification('Pilih gambar terlebih dahulu', 'error');
        return;
    }

    try {
        const response = await AwaitFetchApi(endpoint, method, formData);
        if (response.meta?.code === 200 || response.meta?.code === 201) {
            showNotification(response.meta.message || 'Jadwal berhasil disimpan', 'success');
            closeModal('jadwalModal');
            loadJadwal();
        }
    } catch (error) {
        print.error('Error:', error);
        showNotification('Terjadi kesalahan saat menyimpan jadwal', 'error');
    }
});

// Muat jadwal saat halaman dimuat
document.addEventListener('DOMContentLoaded', loadJadwal);
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Kelas</h1>
        <div>
            <button id="btnAddJurusan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang Sekolah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="jurusanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Kelas -->
<div id="jurusanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Kelas</h3>
            <form id="jurusanForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas</label>
                    <input type="text" id="jurusan" name="jurusan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jenjang Sekolah</label>
                    <select id="jenjang_sekolah" name="jenjang_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Pilih Jenjang Sekolah</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="jurusanModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadJurusan();
        
        // Event listeners for modals
        document.getElementById('btnAddJurusan').addEventListener('click', () => {
            document.getElementById('jurusanForm').reset();
            document.getElementById('jurusanForm').removeAttribute('data-id');
            document.getElementById('modalTitle').textContent = 'Tambah Kelas';
            openModal('jurusanModal');
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('jurusanForm').addEventListener('submit', handleFormSubmit);
    });
    
    async function loadJurusan() {
        try {
            const response = await AwaitFetchApi('admin/jurusan', 'GET');
            console.log('API Response - Kelas:', response);
            
            const tableBody = document.getElementById('jurusanTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data kelas
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const jurusanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            jurusanList.forEach((jurusan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${jurusan.jurusan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                            ${jurusan.jenjang_sekolah}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editJurusan(${jurusan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteJurusan(${jurusan.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data kelas', 'error');
        }
    }
    
    async function editJurusan(id) {
        try {
            const response = await AwaitFetchApi(`admin/jurusan/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const jurusan = response.data;
                document.getElementById('jurusan').value = jurusan.jurusan;
                document.getElementById('jenjang_sekolah').value = jurusan.jenjang_sekolah;
                document.getElementById('jurusanForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = 'Edit Kelas';
                openModal('jurusanModal');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail kelas', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail kelas', 'error');
        }
    }
    
    async function deleteJurusan(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus kelas ini?')) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/jurusan/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || 'Kelas berhasil dihapus', 'success');
                loadJurusan();
            } else {
                showAlert(response.meta?.message || 'Gagal menghapus kelas', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menghapus kelas', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const jurusan = document.getElementById('jurusan').value;
        const jenjang_sekolah = document.getElementById('jenjang_sekolah').value;
        
        if (!jurusan || !jenjang_sekolah) {
            showAlert('Semua field harus diisi', 'error');
            return;
        }
        
        const data = {
            jurusan: jurusan,
            jenjang_sekolah: jenjang_sekolah
        };
        
        try {
            let response;
            
            if (id) {
                response = await AwaitFetchApi(`admin/jurusan/${id}`, 'PUT', data);
            } else {
                response = await AwaitFetchApi('admin/jurusan', 'POST', data);
            }
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showAlert(response.meta.message || 'Kelas berhasil disimpan', 'success');
                closeModal('jurusanModal');
                loadJurusan();
            } else {
                showAlert(response.meta?.message || 'Gagal menyimpan kelas', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menyimpan kelas', 'error');
        }
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    function showAlert(message, type = 'info') {
        Swal.fire({
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    }
</script>
@endpush 
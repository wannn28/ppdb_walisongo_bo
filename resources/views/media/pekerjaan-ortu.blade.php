@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Pekerjaan Orang Tua</h1>
        <div>
            <button id="btnAddPekerjaan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                <i class="fas fa-plus mr-2"></i> Tambah Pekerjaan
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pekerjaan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="pekerjaanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Pekerjaan -->
<div id="pekerjaanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Pekerjaan</h3>
            <form id="pekerjaanForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pekerjaan</label>
                    <input type="text" id="nama_pekerjaan" name="nama_pekerjaan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="pekerjaanModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
        loadPekerjaan();
        
        // Event listeners for modals
        document.getElementById('btnAddPekerjaan').addEventListener('click', () => {
            document.getElementById('pekerjaanForm').reset();
            document.getElementById('pekerjaanForm').removeAttribute('data-id');
            document.getElementById('modalTitle').textContent = 'Tambah Pekerjaan';
            openModal('pekerjaanModal');
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('pekerjaanForm').addEventListener('submit', handleFormSubmit);
    });
    
    async function loadPekerjaan() {
        try {
            const response = await AwaitFetchApi('admin/pekerjaan-ortu', 'GET');
            console.log('API Response - Pekerjaan:', response);
            
            const tableBody = document.getElementById('pekerjaanTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data pekerjaan
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const pekerjaanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            pekerjaanList.forEach((pekerjaan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pekerjaan.nama_pekerjaan}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editPekerjaan(${pekerjaan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePekerjaan(${pekerjaan.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data pekerjaan', 'error');
        }
    }
    
    async function editPekerjaan(id) {
        try {
            const response = await AwaitFetchApi(`admin/pekerjaan-ortu/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const pekerjaan = response.data;
                document.getElementById('nama_pekerjaan').value = pekerjaan.nama_pekerjaan;
                document.getElementById('pekerjaanForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = 'Edit Pekerjaan';
                openModal('pekerjaanModal');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail pekerjaan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail pekerjaan', 'error');
        }
    }
    
    async function deletePekerjaan(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pekerjaan ini?')) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/pekerjaan-ortu/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || 'Pekerjaan berhasil dihapus', 'success');
                loadPekerjaan();
            } else {
                showAlert(response.meta?.message || 'Gagal menghapus pekerjaan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menghapus pekerjaan', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const nama_pekerjaan = document.getElementById('nama_pekerjaan').value;
        
        if (!nama_pekerjaan) {
            showAlert('Nama pekerjaan tidak boleh kosong', 'error');
            return;
        }
        
        const data = {
            nama_pekerjaan: nama_pekerjaan
        };
        
        try {
            let response;
            
            if (id) {
                response = await AwaitFetchApi(`admin/pekerjaan-ortu/${id}`, 'PUT', data);
            } else {
                response = await AwaitFetchApi('admin/pekerjaan-ortu', 'POST', data);
            }
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showAlert(response.meta.message || 'Pekerjaan berhasil disimpan', 'success');
                closeModal('pekerjaanModal');
                loadPekerjaan();
            } else {
                showAlert(response.meta?.message || 'Gagal menyimpan pekerjaan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menyimpan pekerjaan', 'error');
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
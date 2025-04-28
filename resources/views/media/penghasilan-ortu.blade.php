@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Penghasilan Orang Tua</h1>
        <div>
            <button id="btnAddPenghasilan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                <i class="fas fa-plus mr-2"></i> Tambah Penghasilan
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghasilan Orang Tua</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="penghasilanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Penghasilan -->
<div id="penghasilanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Penghasilan</h3>
            <form id="penghasilanForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Penghasilan Orang Tua</label>
                    <input type="text" id="penghasilan_ortu" name="penghasilan_ortu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="penghasilanModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
        loadPenghasilan();
        
        // Event listeners for modals
        document.getElementById('btnAddPenghasilan').addEventListener('click', () => {
            document.getElementById('penghasilanForm').reset();
            document.getElementById('penghasilanForm').removeAttribute('data-id');
            document.getElementById('modalTitle').textContent = 'Tambah Penghasilan';
            openModal('penghasilanModal');
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('penghasilanForm').addEventListener('submit', handleFormSubmit);
    });
    
    async function loadPenghasilan() {
        try {
            const response = await AwaitFetchApi('admin/penghasilan-ortu', 'GET');
            console.log('API Response - Penghasilan:', response);
            
            const tableBody = document.getElementById('penghasilanTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data penghasilan
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const penghasilanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            penghasilanList.forEach((penghasilan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${penghasilan.penghasilan_ortu}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editPenghasilan(${penghasilan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePenghasilan(${penghasilan.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data penghasilan', 'error');
        }
    }
    
    async function editPenghasilan(id) {
        try {
            const response = await AwaitFetchApi(`admin/penghasilan-ortu/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const penghasilan = response.data;
                document.getElementById('penghasilan_ortu').value = penghasilan.penghasilan_ortu;
                document.getElementById('penghasilanForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = 'Edit Penghasilan';
                openModal('penghasilanModal');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail penghasilan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail penghasilan', 'error');
        }
    }
    
    async function deletePenghasilan(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus penghasilan ini?')) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/penghasilan-ortu/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || 'Penghasilan berhasil dihapus', 'success');
                loadPenghasilan();
            } else {
                showAlert(response.meta?.message || 'Gagal menghapus penghasilan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menghapus penghasilan', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const penghasilan_ortu = document.getElementById('penghasilan_ortu').value;
        
        if (!penghasilan_ortu) {
            showAlert('Penghasilan orang tua tidak boleh kosong', 'error');
            return;
        }
        
        const data = {
            penghasilan_ortu: penghasilan_ortu
        };
        
        try {
            let response;
            
            if (id) {
                response = await AwaitFetchApi(`admin/penghasilan-ortu/${id}`, 'PUT', data);
            } else {
                response = await AwaitFetchApi('admin/penghasilan-ortu', 'POST', data);
            }
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showAlert(response.meta.message || 'Penghasilan berhasil disimpan', 'success');
                closeModal('penghasilanModal');
                loadPenghasilan();
            } else {
                showAlert(response.meta?.message || 'Gagal menyimpan penghasilan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menyimpan penghasilan', 'error');
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
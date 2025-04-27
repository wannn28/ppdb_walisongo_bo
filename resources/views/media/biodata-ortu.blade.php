@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Biodata Orang Tua</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ayah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Ibu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Ayah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan Ibu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghasilan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="biodataOrtuTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit Biodata Ortu -->
<div id="biodataOrtuModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-1/2 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Biodata Orang Tua</h3>
            <form id="biodataOrtuForm">
                <div class="grid grid-cols-2 gap-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ayah</label>
                        <input type="text" id="nama_ayah" name="nama_ayah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Ibu</label>
                        <input type="text" id="nama_ibu" name="nama_ibu" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">No Telepon</label>
                        <input type="text" id="no_telp" name="no_telp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pekerjaan Ayah</label>
                        <select id="pekerjaan_ayah_id" name="pekerjaan_ayah_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Pekerjaan Ayah</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pekerjaan Ibu</label>
                        <select id="pekerjaan_ibu_id" name="pekerjaan_ibu_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Pekerjaan Ibu</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Penghasilan Ortu</label>
                        <select id="penghasilan_ortu_id" name="penghasilan_ortu_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <option value="">Pilih Penghasilan</option>
                            <option value="1">< Rp 1.000.000</option>
                            <option value="2">Rp 1.000.000 - Rp 3.000.000</option>
                            <option value="3">Rp 3.000.000 - Rp 5.000.000</option>
                            <option value="4">Rp 5.000.000 - Rp 10.000.000</option>
                            <option value="5">> Rp 10.000.000</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" data-close-modal="biodataOrtuModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
    let pekerjaanList = [];
    
    document.addEventListener('DOMContentLoaded', () => {
        loadPekerjaanOptions();
        loadBiodataOrtu();
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('biodataOrtuForm').addEventListener('submit', handleFormSubmit);
    });
    
    async function loadPekerjaanOptions() {
        try {
            const response = await AwaitFetchApi('admin/pekerjaan-ortu', 'GET');
            if (response.meta?.code === 200) {
                // Check if data is in response.data or response.data.data based on API structure
                pekerjaanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
                
                const pekerjaanAyahSelect = document.getElementById('pekerjaan_ayah_id');
                const pekerjaanIbuSelect = document.getElementById('pekerjaan_ibu_id');
                
                // Clear existing options except first one
                pekerjaanAyahSelect.innerHTML = '<option value="">Pilih Pekerjaan Ayah</option>';
                pekerjaanIbuSelect.innerHTML = '<option value="">Pilih Pekerjaan Ibu</option>';
                
                // Add options
                pekerjaanList.forEach(pekerjaan => {
                    pekerjaanAyahSelect.innerHTML += `<option value="${pekerjaan.id}">${pekerjaan.nama_pekerjaan}</option>`;
                    pekerjaanIbuSelect.innerHTML += `<option value="${pekerjaan.id}">${pekerjaan.nama_pekerjaan}</option>`;
                });
            } else {
                showAlert(response.meta?.message || 'Gagal memuat data pekerjaan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data pekerjaan', 'error');
        }
    }
    
    async function loadBiodataOrtu() {
        try {
            const response = await AwaitFetchApi('admin/biodata-ortu', 'GET');
            console.log('API Response - Biodata Ortu:', response);
            
            const tableBody = document.getElementById('biodataOrtuTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data biodata orang tua
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const biodataList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            biodataList.forEach((biodata, index) => {
                // Find pekerjaan names
                const pekerjaanAyah = pekerjaanList.find(p => p.id === biodata.pekerjaan_ayah_id)?.nama_pekerjaan || '-';
                const pekerjaanIbu = pekerjaanList.find(p => p.id === biodata.pekerjaan_ibu_id)?.nama_pekerjaan || '-';
                
                // Format penghasilan
                let penghasilan = '-';
                switch(biodata.penghasilan_ortu_id) {
                    case 1: penghasilan = '< Rp 1.000.000'; break;
                    case 2: penghasilan = 'Rp 1.000.000 - Rp 3.000.000'; break;
                    case 3: penghasilan = 'Rp 3.000.000 - Rp 5.000.000'; break;
                    case 4: penghasilan = 'Rp 5.000.000 - Rp 10.000.000'; break;
                    case 5: penghasilan = '> Rp 10.000.000'; break;
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.nama_ayah || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.nama_ibu || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.no_telp || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pekerjaanAyah}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pekerjaanIbu}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${penghasilan}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editBiodataOrtu(${biodata.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteBiodataOrtu(${biodata.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data biodata orang tua', 'error');
        }
    }
    
    async function editBiodataOrtu(id) {
        try {
            const response = await AwaitFetchApi(`admin/biodata-ortu/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const biodata = response.data;
                
                document.getElementById('nama_ayah').value = biodata.nama_ayah || '';
                document.getElementById('nama_ibu').value = biodata.nama_ibu || '';
                document.getElementById('no_telp').value = biodata.no_telp || '';
                document.getElementById('pekerjaan_ayah_id').value = biodata.pekerjaan_ayah_id || '';
                document.getElementById('pekerjaan_ibu_id').value = biodata.pekerjaan_ibu_id || '';
                document.getElementById('penghasilan_ortu_id').value = biodata.penghasilan_ortu_id || '';
                
                document.getElementById('biodataOrtuForm').setAttribute('data-id', id);
                openModal('biodataOrtuModal');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail biodata orang tua', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail biodata orang tua', 'error');
        }
    }
    
    async function deleteBiodataOrtu(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus biodata orang tua ini?')) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/biodata-ortu/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || 'Biodata orang tua berhasil dihapus', 'success');
                loadBiodataOrtu();
            } else {
                showAlert(response.meta?.message || 'Gagal menghapus biodata orang tua', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat menghapus biodata orang tua', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        
        if (!id) {
            showAlert('ID biodata tidak ditemukan', 'error');
            return;
        }
        
        const nama_ayah = document.getElementById('nama_ayah').value;
        const nama_ibu = document.getElementById('nama_ibu').value;
        const no_telp = document.getElementById('no_telp').value;
        const pekerjaan_ayah_id = document.getElementById('pekerjaan_ayah_id').value;
        const pekerjaan_ibu_id = document.getElementById('pekerjaan_ibu_id').value;
        const penghasilan_ortu_id = document.getElementById('penghasilan_ortu_id').value;
        
        if (!nama_ayah || !nama_ibu || !no_telp || !pekerjaan_ayah_id || !pekerjaan_ibu_id || !penghasilan_ortu_id) {
            showAlert('Semua field harus diisi', 'error');
            return;
        }
        
        const data = {
            nama_ayah,
            nama_ibu,
            no_telp,
            pekerjaan_ayah_id: parseInt(pekerjaan_ayah_id, 10),
            pekerjaan_ibu_id: parseInt(pekerjaan_ibu_id, 10),
            penghasilan_ortu_id: parseInt(penghasilan_ortu_id, 10)
        };
        
        try {
            const response = await AwaitFetchApi(`admin/biodata-ortu/${id}`, 'PUT', data);
            
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || 'Biodata orang tua berhasil diperbarui', 'success');
                closeModal('biodataOrtuModal');
                loadBiodataOrtu();
            } else {
                showAlert(response.meta?.message || 'Gagal memperbarui biodata orang tua', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memperbarui biodata orang tua', 'error');
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
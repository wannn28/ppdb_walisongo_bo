@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Biaya</h1>
        <div>
            <button id="btnAddBiaya" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                <i class="fas fa-plus mr-2"></i> Tambah Biaya
            </button>
        </div>
    </div>
    
    <!-- Tab navigation -->
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button class="tab-button inline-block p-4 border-b-2 border-blue-500 rounded-t-lg active" data-target="biayaPendaftaran">
                    Biaya Pendaftaran
                </button>
            </li>
            <li class="mr-2">
                <button class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-target="pengajuanBiaya">
                    Pengajuan Biaya
                </button>
            </li>
        </ul>
    </div>
    
    <!-- Biaya Pendaftaran Tab -->
    <div id="biayaPendaftaran" class="tab-content bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="biayaPendaftaranTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
    
    <!-- Pengajuan Biaya Tab -->
    <div id="pengajuanBiaya" class="tab-content bg-white rounded-lg shadow-md overflow-hidden hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="pengajuanBiayaTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah/Edit Biaya -->
<div id="biayaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Biaya</h3>
            <form id="biayaForm" enctype="multipart/form-data">
                <input type="hidden" id="biayaType" name="biayaType" value="pendaftaran">
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Biaya</label>
                    <select id="selectBiayaType" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="pendaftaran">Biaya Pendaftaran</option>
                        <option value="pengajuan">Pengajuan Biaya</option>
                    </select>
                </div>
                
                <div id="pengajuanFields" class="hidden">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                        <select id="tipeKelas" name="tipe_kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Kelas</option>
                            <option value="reguler">Reguler</option>
                            <option value="unggulan">Unggulan</option>
                        </select>
                    </div>
                    
                    <div id="regulerFields" class="hidden">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenjang Sekolah</label>
                            <select id="jenjangSekolah" name="jenjang_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="">Pilih Jenjang</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Upload Gambar</label>
                            <input type="file" id="imageUpload" name="image" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF (Max: 2MB)</p>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nominal</label>
                    <input type="number" id="nominal" name="nominal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="biayaModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
    let currentTab = 'biayaPendaftaran';
    
    document.addEventListener('DOMContentLoaded', () => {
        loadBiayaPendaftaran();
        loadPengajuanBiaya();
        
        // Tab navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-target');
                
                // Update active tab button
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active', 'border-blue-500');
                    btn.classList.add('border-transparent');
                });
                button.classList.add('active', 'border-blue-500');
                button.classList.remove('border-transparent');
                
                // Show target tab content, hide others
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                document.getElementById(target).classList.remove('hidden');
                
                // Update current tab
                currentTab = target;
                
                // Update add button text
                document.getElementById('btnAddBiaya').innerHTML = `<i class="fas fa-plus mr-2"></i> Tambah ${currentTab === 'biayaPendaftaran' ? 'Biaya Pendaftaran' : 'Pengajuan Biaya'}`;
            });
        });
        
        // Event listeners for modals
        document.getElementById('btnAddBiaya').addEventListener('click', () => {
            document.getElementById('biayaForm').reset();
            document.getElementById('biayaForm').removeAttribute('data-id');
            
            const biayaType = currentTab === 'biayaPendaftaran' ? 'pendaftaran' : 'pengajuan';
            document.getElementById('biayaType').value = biayaType;
            document.getElementById('selectBiayaType').value = biayaType;
            
            // Reset and hide all conditionally shown fields
            document.getElementById('pengajuanFields').classList.add('hidden');
            document.getElementById('regulerFields').classList.add('hidden');
            
            // Show pengajuan fields if needed
            if (biayaType === 'pengajuan') {
                document.getElementById('pengajuanFields').classList.remove('hidden');
            }
            
            document.getElementById('modalTitle').textContent = currentTab === 'biayaPendaftaran' ? 'Tambah Biaya Pendaftaran' : 'Tambah Pengajuan Biaya';
            openModal('biayaModal');
        });
        
        // Type selector in modal
        document.getElementById('selectBiayaType').addEventListener('change', function() {
            const biayaType = this.value;
            document.getElementById('biayaType').value = biayaType;
            document.getElementById('modalTitle').textContent = biayaType === 'pendaftaran' ? 'Tambah Biaya Pendaftaran' : 'Tambah Pengajuan Biaya';
            
            // Show/hide pengajuan-specific fields
            if (biayaType === 'pengajuan') {
                document.getElementById('pengajuanFields').classList.remove('hidden');
            } else {
                document.getElementById('pengajuanFields').classList.add('hidden');
                document.getElementById('regulerFields').classList.add('hidden');
            }
        });
        
        // Tipe Kelas change handler
        document.getElementById('tipeKelas').addEventListener('change', function() {
            const tipeKelas = this.value;
            
            // Hide fields first
            document.getElementById('regulerFields').classList.add('hidden');
            
            // Show relevant fields based on selection
            if (tipeKelas === 'reguler') {
                document.getElementById('regulerFields').classList.remove('hidden');
            }
        });
        
        // Jenjang Sekolah change handler
        document.getElementById('jenjangSekolah').addEventListener('change', function() {
            // We don't need to do anything here anymore
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('biayaForm').addEventListener('submit', handleFormSubmit);
    });
    
    async function loadBiayaPendaftaran() {
        try {
            const response = await AwaitFetchApi('admin/biaya-pendaftaran', 'GET');
            print.log('API Response - Biaya Pendaftaran:', response);
            if (response.meta?.code === 200) {
                const biayaList = response.data || [];
                const tableBody = document.getElementById('biayaPendaftaranTableBody');
                
                tableBody.innerHTML = '';
                
                if (biayaList.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data biaya pendaftaran
                        </td>
                    `;
                    tableBody.appendChild(emptyRow);
                    return;
                }
                
                biayaList.forEach((biaya, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp ${formatNumber(biaya.nominal)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editBiaya('pendaftaran', ${biaya.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteBiaya('pendaftaran', ${biaya.id})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                showNotification(response.meta?.message || 'Gagal memuat data biaya pendaftaran', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data biaya pendaftaran', 'error');
        }
    }
    
    async function loadPengajuanBiaya() {
        try {
            const response = await AwaitFetchApi('admin/pengajuan-biaya', 'GET');
            print.log('API Response - Pengajuan Biaya:', response);
            if (response.meta?.code === 200) {
                const biayaList = response.data || [];
                const tableBody = document.getElementById('pengajuanBiayaTableBody');
                
                tableBody.innerHTML = '';
                
                if (biayaList.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pengajuan biaya
                        </td>
                    `;
                    tableBody.appendChild(emptyRow);
                    return;
                }
                
                biayaList.forEach((biaya, index) => {
                    const row = document.createElement('tr');
                    
                    let kelas = biaya.jurusan === 'reguler' ? 'Reguler' : 'Unggulan';
                    let detail = biaya.jurusan === 'reguler' 
                        ? `${biaya.jenjang_sekolah || '-'}`
                        : `-`; // For unggulan, no detail needed
                    
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${biaya.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap">Rp ${formatNumber(biaya.nominal)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${biaya.jurusan === 'reguler' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                                ${kelas}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${detail}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editBiaya('pengajuan', ${biaya.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteBiaya('pengajuan', ${biaya.id})" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                showNotification(response.meta?.message || 'Gagal memuat data pengajuan biaya', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data pengajuan biaya', 'error');
        }
    }
    
    async function editBiaya(type, id) {
        try {
            const endpoint = type === 'pendaftaran' ? 'admin/biaya-pendaftaran' : 'admin/pengajuan-biaya';
            const response = await AwaitFetchApi(`${endpoint}/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const biaya = response.data;
                
                // Reset form
                document.getElementById('biayaForm').reset();
                
                // Set basic fields
                document.getElementById('biayaType').value = type;
                document.getElementById('selectBiayaType').value = type;
                document.getElementById('nominal').value = biaya.nominal;
                
                // Handle pengajuan-specific fields
                if (type === 'pengajuan') {
                    document.getElementById('pengajuanFields').classList.remove('hidden');
                    
                    if (biaya.jurusan === 'reguler') {
                        document.getElementById('tipeKelas').value = 'reguler';
                        document.getElementById('regulerFields').classList.remove('hidden');
                        
                        if (biaya.jenjang_sekolah) {
                            document.getElementById('jenjangSekolah').value = biaya.jenjang_sekolah;
                        }
                    } else {
                        document.getElementById('tipeKelas').value = 'unggulan';
                        document.getElementById('regulerFields').classList.add('hidden');
                    }
                } else {
                    document.getElementById('pengajuanFields').classList.add('hidden');
                    document.getElementById('regulerFields').classList.add('hidden');
                }
                
                document.getElementById('biayaForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = type === 'pendaftaran' ? 'Edit Biaya Pendaftaran' : 'Edit Pengajuan Biaya';
                openModal('biayaModal');
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification(`Terjadi kesalahan saat memuat detail ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    async function deleteBiaya(type, id) {
        const confirmMessage = type === 'pendaftaran' ? 
            'Apakah Anda yakin ingin menghapus biaya pendaftaran ini?' : 
            'Apakah Anda yakin ingin menghapus pengajuan biaya ini?';
            
            const result = await showDeleteConfirmation(confirmMessage);

            if (!result.isConfirmed) {
                return;
            }
        
        try {
            const endpoint = type === 'pendaftaran' ? 'admin/biaya-pendaftaran' : 'admin/pengajuan-biaya';
            const response = await AwaitFetchApi(`${endpoint}/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || `${type === 'pendaftaran' ? 'Biaya pendaftaran' : 'Pengajuan biaya'} berhasil dihapus`, 'success');
                if (type === 'pendaftaran') {
                    loadBiayaPendaftaran();
                } else {
                    loadPengajuanBiaya();
                }
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification(`Terjadi kesalahan saat menghapus ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const biayaType = document.getElementById('biayaType').value;
        const nominal = document.getElementById('nominal').value;
        
        if (!nominal) {
            showNotification('Nominal tidak boleh kosong', 'error');
            return;
        }
        
        try {
            let response;
            let endpoint;
            let data = {
                nominal: parseInt(nominal, 10)
            };
            
            // Handle different submission paths based on biaya type
            if (biayaType === 'pendaftaran') {
                endpoint = 'admin/biaya-pendaftaran';
                
                if (id) {
                    response = await AwaitFetchApi(`${endpoint}/${id}`, 'PUT', data);
                } else {
                    response = await AwaitFetchApi(endpoint, 'POST', data);
                }
            } else if (biayaType === 'pengajuan') {
                const tipeKelas = document.getElementById('tipeKelas').value;
                
                if (!tipeKelas) {
                    showNotification('Kelas harus dipilih', 'error');
                    return;
                }
                
                if (tipeKelas === 'reguler') {
                    const jenjangSekolah = document.getElementById('jenjangSekolah').value;
                    const imageUpload = document.getElementById('imageUpload').files[0];
                    
                    if (!jenjangSekolah) {
                        showNotification('Jenjang sekolah harus dipilih', 'error');
                        return;
                    }
                    
                    if (!imageUpload) {
                        showNotification('Gambar harus diupload', 'error');
                        return;
                    }
                    
                    // For reguler, we need to upload the image first
                    const formData = new FormData();
                    formData.append('image', imageUpload);
                    formData.append('jenjang_sekolah', jenjangSekolah);
                    formData.append('jurusan', 'reguler');
                    
                    // Upload image and data to the media endpoint
                    response = await AwaitFetchApi('admin/media/pengajuan-biaya', 'POST', formData, true);
                    
                    if (response.meta?.code === 201) {
                        // If image upload successful, proceed with creating/updating pengajuan biaya
                        data.jenjang_sekolah = jenjangSekolah;
                        data.jurusan = 'reguler';
                        
                        if (id) {
                            // For update
                            endpoint = `admin/pengajuan-biaya/${id}`;
                            response = await AwaitFetchApi(endpoint, 'PUT', data);
                        } else {
                            // For create
                            endpoint = 'admin/pengajuan-biaya/reguler';
                            response = await AwaitFetchApi(endpoint, 'POST', data);
                        }
                    }
                } else if (tipeKelas === 'unggulan') {
                    // For unggulan, we don't need jurusan anymore
                    data.jurusan = 'unggulan'; // Set a fixed value for jurusan
                    
                    if (id) {
                        // For update
                        endpoint = `admin/pengajuan-biaya/${id}`;
                        response = await AwaitFetchApi(endpoint, 'PUT', data);
                    } else {
                        // For create - using the book-vee endpoint as specified for unggulan
                        endpoint = 'admin/pengajuan-biaya/book-vee';
                        response = await AwaitFetchApi(endpoint, 'POST', data);
                    }
                }
            }
            
            print.log('Response:', response);
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showNotification(response.meta.message || `${biayaType === 'pendaftaran' ? 'Biaya pendaftaran' : 'Pengajuan biaya'} berhasil disimpan`, 'success');
                closeModal('biayaModal');
                
                if (biayaType === 'pendaftaran') {
                    loadBiayaPendaftaran();
                } else {
                    loadPengajuanBiaya();
                }
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification(`Terjadi kesalahan saat menyimpan ${biayaType === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>

<style>
    .tab-button.active {
        color: #3b82f6;
        border-color: #3b82f6;
    }
</style>
@endpush
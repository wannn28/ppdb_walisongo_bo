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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
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
            <form id="biayaForm">
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
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenjang Sekolah</label>
                        <select id="jenjangSekolah" name="jenjang_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Jenjang</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelas</label>
                        <select id="jenisKelas" name="jenis_kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Jenis Kelas</option>
                            <option value="reguler">Reguler</option>
                            <option value="unggulan">Unggulan</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                        <select id="kelas" name="kelas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Pilih Kelas</option>
                            <!-- Options will be populated dynamically -->
                        </select>
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
    let jurusanList = []; // To store jurusan data
    
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
            
            // Fetch jurusan data when opening modal for pengajuan
            if (biayaType === 'pengajuan') {
                fetchJurusanData();
                document.getElementById('pengajuanFields').classList.remove('hidden');
            } else {
                document.getElementById('pengajuanFields').classList.add('hidden');
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
                fetchJurusanData();
                document.getElementById('pengajuanFields').classList.remove('hidden');
            } else {
                document.getElementById('pengajuanFields').classList.add('hidden');
            }
        });
        
        // Jenjang & Jenis Kelas change handlers
        document.getElementById('jenisKelas').addEventListener('change', updateKelasOptions);
        document.getElementById('jenjangSekolah').addEventListener('change', () => {
            // Reset jenis kelas when jenjang changes
            document.getElementById('jenisKelas').value = '';
            document.getElementById('kelas').innerHTML = '<option value="">Pilih Kelas</option>';
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
    
    // Fetch jurusan data from API
    async function fetchJurusanData() {
        try {
            const response = await AwaitFetchApi('admin/jurusan', 'GET');
            print.log('API Response - Jurusan:', response);
            if (response.meta?.code === 200) {
                jurusanList = response.data || [];
            } else {
                print.error('Failed to fetch jurusan data:', response.meta?.message);
            }
        } catch (error) {
            print.error('Error fetching jurusan data:', error);
        }
    }
    
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
                showAlert(response.meta?.message || 'Gagal memuat data biaya pendaftaran', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data biaya pendaftaran', 'error');
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
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pengajuan biaya
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
                                ${biaya.jenjang_sekolah || ''} - ${biaya.jenis_kelas || ''} - ${biaya.kelas || ''}
                            </span>
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
                showAlert(response.meta?.message || 'Gagal memuat data pengajuan biaya', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data pengajuan biaya', 'error');
        }
    }
    
    async function editBiaya(type, id) {
        try {
            const endpoint = type === 'pendaftaran' ? 'admin/biaya-pendaftaran' : 'admin/pengajuan-biaya';
            const response = await AwaitFetchApi(`${endpoint}/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const biaya = response.data;
                document.getElementById('biayaType').value = type;
                document.getElementById('selectBiayaType').value = type;
                document.getElementById('nominal').value = biaya.nominal;
                
                // Set pengajuan fields if relevant
                if (type === 'pengajuan') {
                    await fetchJurusanData(); // Fetch jurusan data before setting fields
                    document.getElementById('pengajuanFields').classList.remove('hidden');
                    if (biaya.jenjang_sekolah) {
                        document.getElementById('jenjangSekolah').value = biaya.jenjang_sekolah;
                    }
                    if (biaya.jenis_kelas) {
                        document.getElementById('jenisKelas').value = biaya.jenis_kelas;
                        updateKelasOptions();
                    }
                    if (biaya.kelas) {
                        document.getElementById('kelas').value = biaya.kelas;
                    }
                } else {
                    document.getElementById('pengajuanFields').classList.add('hidden');
                }
                
                document.getElementById('biayaForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = type === 'pendaftaran' ? 'Edit Biaya Pendaftaran' : 'Edit Pengajuan Biaya';
                openModal('biayaModal');
            } else {
                showAlert(response.meta?.message || `Gagal memuat detail ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showAlert(`Terjadi kesalahan saat memuat detail ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    async function deleteBiaya(type, id) {
        const confirmMessage = type === 'pendaftaran' ? 
            'Apakah Anda yakin ingin menghapus biaya pendaftaran ini?' : 
            'Apakah Anda yakin ingin menghapus pengajuan biaya ini?';
            
        if (!confirm(confirmMessage)) {
            return;
        }
        
        try {
            const endpoint = type === 'pendaftaran' ? 'admin/biaya-pendaftaran' : 'admin/pengajuan-biaya';
            const response = await AwaitFetchApi(`${endpoint}/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showAlert(response.meta.message || `${type === 'pendaftaran' ? 'Biaya pendaftaran' : 'Pengajuan biaya'} berhasil dihapus`, 'success');
                if (type === 'pendaftaran') {
                    loadBiayaPendaftaran();
                } else {
                    loadPengajuanBiaya();
                }
            } else {
                showAlert(response.meta?.message || `Gagal menghapus ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showAlert(`Terjadi kesalahan saat menghapus ${type === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const biayaType = document.getElementById('biayaType').value;
        const nominal = document.getElementById('nominal').value;
        
        if (!nominal) {
            showAlert('Nominal tidak boleh kosong', 'error');
            return;
        }
        
        const data = {
            nominal: parseInt(nominal, 10)
        };
        
        // Add pengajuan-specific fields if relevant
        if (biayaType === 'pengajuan') {
            const jenjangSekolah = document.getElementById('jenjangSekolah').value;
            const jenisKelas = document.getElementById('jenisKelas').value;
            const kelas = document.getElementById('kelas').value;
            
            if (!jenjangSekolah) {
                showAlert('Jenjang sekolah harus dipilih', 'error');
                return;
            }
            
            if (!jenisKelas) {
                showAlert('Jenis kelas harus dipilih', 'error');
                return;
            }
            
            if (!kelas) {
                showAlert('Kelas harus dipilih', 'error');
                return;
            }
            
            data.jenjang_sekolah = jenjangSekolah;
            data.jenis_kelas = jenisKelas;
            data.kelas = kelas;
        }
        
        print.log('Sending data:', data);
        
        try {
            let response;
            const endpoint = biayaType === 'pendaftaran' ? 'admin/biaya-pendaftaran' : 'admin/pengajuan-biaya';
            
            if (id) {
                response = await AwaitFetchApi(`${endpoint}/${id}`, 'PUT', data);
            } else {
                response = await AwaitFetchApi(endpoint, 'POST', data);
            }
            
            print.log('Response:', response);
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showAlert(response.meta.message || `${biayaType === 'pendaftaran' ? 'Biaya pendaftaran' : 'Pengajuan biaya'} berhasil disimpan`, 'success');
                closeModal('biayaModal');
                
                if (biayaType === 'pendaftaran') {
                    loadBiayaPendaftaran();
                } else {
                    loadPengajuanBiaya();
                }
            } else {
                showAlert(response.meta?.message || `Gagal menyimpan ${biayaType === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showAlert(`Terjadi kesalahan saat menyimpan ${biayaType === 'pendaftaran' ? 'biaya pendaftaran' : 'pengajuan biaya'}`, 'error');
        }
    }
    
    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
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
    
    function updateKelasOptions() {
        const jenisKelas = document.getElementById('jenisKelas').value;
        const jenjang = document.getElementById('jenjangSekolah').value;
        const kelasSelect = document.getElementById('kelas');
        
        // Clear existing options
        kelasSelect.innerHTML = '<option value="">Pilih Kelas</option>';
        
        if (!jenisKelas || !jenjang) return;
        
        if (jenisKelas === 'reguler') {
            // For regular classes, show only "Reguler" option
            const optElement = document.createElement('option');
            optElement.value = "reguler";
            optElement.textContent = "Reguler";
            kelasSelect.appendChild(optElement);
        } else if (jenisKelas === 'unggulan') {
            // For unggulan classes, show jurusan filtered by jenjang_sekolah
            const filteredJurusan = jurusanList.filter(j => j.jenjang_sekolah === jenjang);
            
            if (filteredJurusan.length === 0) {
                const optElement = document.createElement('option');
                optElement.value = "";
                optElement.textContent = "Tidak ada jurusan tersedia";
                kelasSelect.appendChild(optElement);
            } else {
                filteredJurusan.forEach(jurusan => {
                    const optElement = document.createElement('option');
                    optElement.value = jurusan.id;
                    optElement.textContent = jurusan.jurusan;
                    kelasSelect.appendChild(optElement);
                });
            }
        }
    }
</script>

<style>
    .tab-button.active {
        color: #3b82f6;
        border-color: #3b82f6;
    }
</style>
@endpush
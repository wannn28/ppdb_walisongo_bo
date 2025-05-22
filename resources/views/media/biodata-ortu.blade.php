@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Biodata Orang Tua</h1>
        <button id="btnTrash" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center"
            onclick="openTrashModal()">
            <i class="fas fa-trash mr-2"></i> Trash
        </button>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-x-auto w-full">
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

<!-- Modal Trash Biodata Ortu -->
<div id="trashBiodataOrtuModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Biodata Orang Tua Terhapus</h3>
            <button onclick="closeModal('trashBiodataOrtuModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-white rounded-lg shadow-md overflow-x-auto w-full">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dihapus Pada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="trashBiodataOrtuTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
            
            <!-- Pagination for trash -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button id="trash-prev-page" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </button>
                    <button id="trash-next-page" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium" id="trash-pagination-start">0</span>
                            to
                            <span class="font-medium" id="trash-pagination-end">0</span>
                            of
                            <span class="font-medium" id="trash-pagination-total">0</span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button id="trash-prev-page" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="trash-page-numbers" class="flex"></div>
                            <button id="trash-next-page" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let pekerjaanList = [];
    
    let trashCurrentPage = 1;
    let trashTotalPages = 1;

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
        
        // Trash pagination event listeners
        document.getElementById('trash-prev-page').addEventListener('click', () => {
            if (trashCurrentPage > 1) {
                loadTrashBiodataOrtu(trashCurrentPage - 1);
            }
        });
        
        document.getElementById('trash-next-page').addEventListener('click', () => {
            if (trashCurrentPage < trashTotalPages) {
                loadTrashBiodataOrtu(trashCurrentPage + 1);
            }
        });
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
                showNotification(response.meta?.message || 'Gagal memuat data pekerjaan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data pekerjaan', 'error');
        }
    }
    
    async function loadBiodataOrtu() {
        try {
            const response = await AwaitFetchApi('admin/biodata-ortu', 'GET');
            print.log('API Response - Biodata Ortu:', response);
            
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
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data biodata orang tua', 'error');
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
                showNotification(response.meta?.message || 'Gagal memuat detail biodata orang tua', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail biodata orang tua', 'error');
        }
    }
    
    async function deleteBiodataOrtu(id) {
        const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus biodata orang tua ini?');
        
        if (!result.isConfirmed) {
            return;
        }
        

        try {
            const response = await AwaitFetchApi(`admin/biodata-ortu/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Biodata orang tua berhasil dihapus', 'success');
                loadBiodataOrtu();
            } else {
                showNotification(response.meta?.message || 'Gagal menghapus biodata orang tua', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus biodata orang tua', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        
        if (!id) {
            showNotification('ID biodata tidak ditemukan', 'error');
            return;
        }
        
        const nama_ayah = document.getElementById('nama_ayah').value;
        const nama_ibu = document.getElementById('nama_ibu').value;
        const no_telp = document.getElementById('no_telp').value;
        const pekerjaan_ayah_id = document.getElementById('pekerjaan_ayah_id').value;
        const pekerjaan_ibu_id = document.getElementById('pekerjaan_ibu_id').value;
        const penghasilan_ortu_id = document.getElementById('penghasilan_ortu_id').value;
        
        if (!nama_ayah || !nama_ibu || !no_telp || !pekerjaan_ayah_id || !pekerjaan_ibu_id || !penghasilan_ortu_id) {
            showNotification('Semua field harus diisi', 'error');
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
                showNotification(response.meta.message || 'Biodata orang tua berhasil diperbarui', 'success');
                closeModal('biodataOrtuModal');
                loadBiodataOrtu();
            } else {
                showNotification(response.meta?.message || 'Gagal memperbarui biodata orang tua', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memperbarui biodata orang tua', 'error');
        }
    }
    
    async function loadTrashBiodataOrtu(page = 1) {
        trashCurrentPage = page;
        try {
            const params = new URLSearchParams({
                page: page,
                per_page: 10
            });

            const response = await AwaitFetchApi(`admin/biodata-ortus/trash?${params}`, 'GET');
            print.log('API Response - Biodata Ortu Trash:', response);
            
            const tableBody = document.getElementById('trashBiodataOrtuTableBody');
            tableBody.innerHTML = '';
            
            // Check if response has data
            if (!response.data || (Array.isArray(response.data) && response.data.length === 0) || 
                (response.data.data && response.data.data.length === 0)) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada biodata orang tua terhapus
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Determine if response is paginated or direct array
            let biodataList;
            let paginationInfo = { from: 1, to: 0, total: 0, last_page: 1 };
            
            if (Array.isArray(response.data)) {
                // Direct array response
                biodataList = response.data;
                paginationInfo.to = biodataList.length;
                paginationInfo.total = biodataList.length;
            } else {
                // Paginated response
                biodataList = response.data.data || [];
                paginationInfo = {
                    from: response.data.from || 1,
                    to: response.data.to || biodataList.length,
                    total: response.data.total || biodataList.length,
                    last_page: response.data.last_page || 1
                };
            }
            
            // Update pagination info
            document.getElementById('trash-pagination-start').textContent = paginationInfo.from || 1;
            document.getElementById('trash-pagination-end').textContent = paginationInfo.to || biodataList.length;
            document.getElementById('trash-pagination-total').textContent = paginationInfo.total || biodataList.length;
            
            // Enable/disable pagination buttons
            document.getElementById('trash-prev-page').disabled = trashCurrentPage === 1;
            document.getElementById('trash-next-page').disabled = trashCurrentPage === paginationInfo.last_page;
            
            // Update page numbers
            updateTrashPageNumbers(trashCurrentPage, paginationInfo.last_page);
            
            trashTotalPages = paginationInfo.last_page;
            
            let startIndex = paginationInfo.from || 1;
            
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
                    <td class="px-6 py-4 whitespace-nowrap">${startIndex + index}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.nama_ayah || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.nama_ibu || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${biodata.no_telp || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pekerjaanAyah}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pekerjaanIbu}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${penghasilan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>${formatDate(biodata.deleted_at)}</div>
                        <div class="text-sm text-gray-500">${formatDate(biodata.deleted_at, true)?.split(' ')[1] || ''}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="restoreBiodataOrtu(${biodata.id})" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-trash-restore"></i> Restore
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data biodata orang tua terhapus', 'error');
        }
    }
    
    function updateTrashPageNumbers(currentPage, lastPage) {
        const pageNumbersContainer = document.getElementById('trash-page-numbers');
        pageNumbersContainer.innerHTML = '';
        
        // Determine range of page numbers to show
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(lastPage, startPage + 4);
        
        // Adjust start if we're near the end
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }
        
        // Add first page button if not included in range
        if (startPage > 1) {
            addPageButton(1);
            if (startPage > 2) {
                addEllipsis();
            }
        }
        
        // Add page number buttons
        for (let i = startPage; i <= endPage; i++) {
            addPageButton(i);
        }
        
        // Add last page button if not included in range
        if (endPage < lastPage) {
            if (endPage < lastPage - 1) {
                addEllipsis();
            }
            addPageButton(lastPage);
        }
        
        function addPageButton(pageNum) {
            const button = document.createElement('button');
            button.textContent = pageNum;
            button.classList.add('px-3', 'py-1', 'border', 'rounded-md');
            
            if (pageNum === currentPage) {
                button.classList.add('bg-blue-500', 'text-white');
            } else {
                button.classList.add('hover:bg-gray-100');
                button.addEventListener('click', () => loadTrashBiodataOrtu(pageNum));
            }
            
            pageNumbersContainer.appendChild(button);
        }
        
        function addEllipsis() {
            const ellipsis = document.createElement('span');
            ellipsis.textContent = '...';
            ellipsis.classList.add('px-2', 'py-1');
            pageNumbersContainer.appendChild(ellipsis);
        }
    }
    
    async function restoreBiodataOrtu(id) {
        const result = await showDeleteConfirmation(
            'Apakah Anda yakin ingin memulihkan biodata orang tua ini?',
            'Ya, Pulihkan',
            'Batal'
        );
        
        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/biodata-ortu/${id}/restore`, 'PUT');
            
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Biodata orang tua berhasil dipulihkan', 'success');
                loadTrashBiodataOrtu(trashCurrentPage);
                loadBiodataOrtu();
            } else {
                showNotification(response.meta?.message || 'Gagal memulihkan biodata orang tua', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memulihkan biodata orang tua', 'error');
        }
    }
    
    function openTrashModal() {
        loadTrashBiodataOrtu();
        openModal('trashBiodataOrtuModal');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

</script>
@endpush
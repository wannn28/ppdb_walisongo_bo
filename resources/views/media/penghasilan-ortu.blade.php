@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Penghasilan Orang Tua</h1>
        <div class="flex gap-4">
            <button id="btnTrash" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center"
                onclick="openTrashModal()">
                <i class="fas fa-trash mr-2"></i> Trash
            </button>
            <button id="btnAddPenghasilan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Penghasilan
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
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

<!-- Modal Trash Penghasilan -->
<div id="trashPenghasilanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Penghasilan Orang Tua Terhapus</h3>
            <button onclick="closeModal('trashPenghasilanModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-white rounded-lg overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penghasilan Orang Tua</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dihapus Pada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="trashPenghasilanTableBody" class="bg-white divide-y divide-gray-200">
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
    let trashCurrentPage = 1;
    let trashTotalPages = 1;

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
        
        // Trash pagination event listeners
        document.getElementById('trash-prev-page').addEventListener('click', () => {
            if (trashCurrentPage > 1) {
                loadTrashPenghasilan(trashCurrentPage - 1);
            }
        });
        
        document.getElementById('trash-next-page').addEventListener('click', () => {
            if (trashCurrentPage < trashTotalPages) {
                loadTrashPenghasilan(trashCurrentPage + 1);
            }
        });
    });
    
    async function loadPenghasilan() {
        try {
            const response = await AwaitFetchApi('admin/penghasilan-ortu', 'GET');
            print.log('API Response - Penghasilan:', response);
            
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
                    <td class="px-6 py-4 whitespace-nowrap">${penghasilan.id}</td>
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
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data penghasilan', 'error');
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
                showNotification(response.meta?.message || 'Gagal memuat detail penghasilan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail penghasilan', 'error');
        }
    }
    
    async function deletePenghasilan(id) {
        const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus penghasilan ini?');
        
        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/penghasilan-ortu/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Penghasilan berhasil dihapus', 'success');
                loadPenghasilan();
            } else {
                showNotification(response.meta?.message || 'Gagal menghapus penghasilan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus penghasilan', 'error');
        }
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const penghasilan_ortu = document.getElementById('penghasilan_ortu').value;
        
        if (!penghasilan_ortu) {
            showNotification('Penghasilan orang tua tidak boleh kosong', 'error');
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
                showNotification(response.meta.message || 'Penghasilan berhasil disimpan', 'success');
                closeModal('penghasilanModal');
                loadPenghasilan();
            } else {
                showNotification(response.meta?.message || 'Gagal menyimpan penghasilan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat menyimpan penghasilan', 'error');
        }
    }
    
    async function loadTrashPenghasilan(page = 1) {
        trashCurrentPage = page;
        try {
            const params = new URLSearchParams({
                page: page,
                per_page: 10
            });

            const response = await AwaitFetchApi(`admin/penghasilan-ortus/trash?${params}`, 'GET');
            print.log('API Response - Penghasilan Ortu Trash:', response);
            
            const tableBody = document.getElementById('trashPenghasilanTableBody');
            tableBody.innerHTML = '';
            
            // Check if response has data
            if (!response.data || (Array.isArray(response.data) && response.data.length === 0) || 
                (response.data.data && response.data.data.length === 0)) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada penghasilan orang tua terhapus
                        </td>
                    </tr>
                `;
                return;
            }
            
            // Determine if response is paginated or direct array
            let penghasilanList;
            let paginationInfo = { from: 1, to: 0, total: 0, last_page: 1 };
            
            if (Array.isArray(response.data)) {
                // Direct array response
                penghasilanList = response.data;
                paginationInfo.to = penghasilanList.length;
                paginationInfo.total = penghasilanList.length;
            } else {
                // Paginated response
                penghasilanList = response.data.data || [];
                paginationInfo = {
                    from: response.data.from || 1,
                    to: response.data.to || penghasilanList.length,
                    total: response.data.total || penghasilanList.length,
                    last_page: response.data.last_page || 1
                };
            }
            
            // Update pagination info
            document.getElementById('trash-pagination-start').textContent = paginationInfo.from || 1;
            document.getElementById('trash-pagination-end').textContent = paginationInfo.to || penghasilanList.length;
            document.getElementById('trash-pagination-total').textContent = paginationInfo.total || penghasilanList.length;
            
            // Enable/disable pagination buttons
            document.getElementById('trash-prev-page').disabled = trashCurrentPage === 1;
            document.getElementById('trash-next-page').disabled = trashCurrentPage === paginationInfo.last_page;
            
            // Update page numbers
            updateTrashPageNumbers(trashCurrentPage, paginationInfo.last_page);
            
            trashTotalPages = paginationInfo.last_page;
            
            let startIndex = paginationInfo.from || 1;
            
            penghasilanList.forEach((penghasilan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${startIndex + index}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${penghasilan.penghasilan_ortu}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>${formatDate(penghasilan.deleted_at)}</div>
                        <div class="text-sm text-gray-500">${formatDate(penghasilan.deleted_at, true)?.split(' ')[1] || ''}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="restorePenghasilan(${penghasilan.id})" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-trash-restore"></i> Restore
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data penghasilan orang tua terhapus', 'error');
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
                button.addEventListener('click', () => loadTrashPenghasilan(pageNum));
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
    
    async function restorePenghasilan(id) {
        const result = await showDeleteConfirmation(
            'Apakah Anda yakin ingin memulihkan penghasilan orang tua ini?',
            'Ya, Pulihkan',
            'Batal'
        );
        
        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/penghasilan-ortu/${id}/restore`, 'PUT');
            
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Penghasilan orang tua berhasil dipulihkan', 'success');
                loadTrashPenghasilan(trashCurrentPage);
                loadPenghasilan();
            } else {
                showNotification(response.meta?.message || 'Gagal memulihkan penghasilan orang tua', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memulihkan penghasilan orang tua', 'error');
        }
    }
    
    function openTrashModal() {
        loadTrashPenghasilan();
        openModal('trashPenghasilanModal');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
</script>
@endpush 
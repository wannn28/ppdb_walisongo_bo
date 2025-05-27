@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tagihan</h1>
        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="openTrashModal()">
            <i class="fas fa-trash mr-2"></i> Trash
        </button>
    </div>
    
    <!-- Search and Filter Controls -->
    <x-filter resetFunction="resetFilters">
        <x-filter-text 
            id="searchInput" 
            label="Search" 
            placeholder="Cari tagihan..." 
            onChangeFunction="updateSearchFilter" />
        
        <x-filter-select 
            id="statusFilter" 
            label="Status" 
            :options="[''=>'All Status', 'pending'=>'Pending', 'paid'=>'Paid', 'failed'=>'Failed']" 
            onChangeFunction="updateStatusFilter" />
        
        <x-filter-date-range 
            startId="startDate" 
            endId="endDate" 
            label="Date Range" 
            onStartChangeFunction="updateStartDateFilter" 
            onEndChangeFunction="updateEndDateFilter" />
    </x-filter>
    
    <!-- Tagihan Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto w-full mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <x-sortable-header column="id" label="ID" sortFunction="handleSort" />
                    <x-sortable-header column="user" label="User" sortFunction="handleSort" />
                    <x-sortable-header column="nama_tagihan" label="Nama Tagihan" sortFunction="handleSort" />
                    <x-sortable-header column="total" label="Total" sortFunction="handleSort" />
                    <x-sortable-header column="status" label="Status" sortFunction="handleSort" />
                    <x-sortable-header column="created_at" label="Waktu" sortFunction="handleSort" />
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="tagihanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        <!-- Pagination -->
        <x-pagination id="tagihanPagination" loadFunction="loadTagihanPage" />
    </div>
</div>

<!-- Modal Detail Tagihan -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Tagihan</h3>
            <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold mb-4">Informasi Tagihan</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Tagihan</label>
                        <p class="mt-1" id="detail-id">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Tagihan</label>
                        <p class="mt-1" id="detail-nama">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800" id="detail-status">-</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Tagihan</label>
                        <p class="mt-1 text-lg font-semibold" id="detail-total">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">VA Number</label>
                        <p class="mt-1" id="detail-va-number">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Transaction QR ID</label>
                        <p class="mt-1" id="detail-transaction-qr-id">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created Time</label>
                        <p class="mt-1" id="detail-created-time">-</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">Informasi User</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">User ID</label>
                        <p class="mt-1" id="detail-user-id">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama User</label>
                        <p class="mt-1" id="detail-user-name">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Dibuat</label>
                        <p class="mt-1" id="detail-created">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                        <p class="mt-1" id="detail-updated">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">QR Data</label>
                        <p class="mt-1 break-all text-xs" id="detail-qr-data">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Tagihan -->
<div id="tagihanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Tagihan</h3>
            <form id="tagihanForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">User ID</label>
                    <input type="text" id="user_id" name="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Tagihan</label>
                    <input type="text" id="nama_tagihan" name="nama_tagihan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Total</label>
                    <input type="number" id="total" name="total" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="tagihanModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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

<!-- Modal Trash Tagihan -->
<div id="trashModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Tagihan Terhapus</h3>
            <button onclick="closeModal('trashModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-white rounded-lg overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="trashTableBody" class="bg-white divide-y divide-gray-200">
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

<x-table-utils sortVarName="sortBy" directionVarName="sortDirection" />

<script>
    let currentPage = 1;
    let sortBy = 'created_at';
    let sortDirection = 'desc';
    let filters = {
        search: '',
        status: '',
        start_date: '',
        end_date: ''
    };
    
    // For trash
    let trashCurrentPage = 1;
    let trashTotalPages = 1;

    // Initialize the page
    document.addEventListener('DOMContentLoaded', () => {
        loadTagihan();
        
        // Add tambah tagihan button event
        const btnAddTagihan = document.getElementById('btnAddTagihan');
        if (btnAddTagihan) {
            btnAddTagihan.addEventListener('click', () => {
                document.getElementById('tagihanForm').reset();
                document.getElementById('tagihanModal').classList.remove('hidden');
            });
        }
        
        // Setup form submission
        document.getElementById('tagihanForm').addEventListener('submit', handleTagihanSubmit);
        
        // Setup modal close buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                document.getElementById(modalId).classList.add('hidden');
            });
        });
        
        // Setup trash pagination event listeners
        document.getElementById('trash-prev-page').addEventListener('click', () => {
            if (trashCurrentPage > 1) {
                loadTrashData(trashCurrentPage - 1);
            }
        });
        
        document.getElementById('trash-next-page').addEventListener('click', () => {
            if (trashCurrentPage < trashTotalPages) {
                loadTrashData(trashCurrentPage + 1);
            }
        });
    });

    // Filter update functions
    function updateSearchFilter(value) {
        filters.search = value;
        loadTagihan();
    }
    
    function updateStatusFilter(value) {
        filters.status = value;
        loadTagihan();
    }
    
    function updateStartDateFilter(value) {
        filters.start_date = value;
        loadTagihan();
    }
    
    function updateEndDateFilter(value) {
        filters.end_date = value;
        loadTagihan();
    }
    
    function resetFilters() {
        filters = {
            search: '',
            status: '',
            start_date: '',
            end_date: ''
        };
        document.getElementById('searchInput').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        loadTagihan();
    }
    
    // Sort function
    function handleSort(column) {
        handleSortGeneric(column, loadTagihan);
    }
    
    // Pagination function
    function loadTagihanPage(page) {
        currentPage = page;
        loadTagihan();
    }

    // Main data loading function
    async function loadTagihan() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                sort_by: sortBy,
                order_by: sortDirection,
                ...filters
            });

            const response = await AwaitFetchApi(`admin/tagihan?${params}`, 'GET');
            
            const tableBody = document.getElementById('tagihanTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data tagihan
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const tagihanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            tagihanList.forEach((tagihan) => {
                // Define status color based on status value
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = tagihan.status;
                
                if (tagihan.status === 'paid' || tagihan.status === 1) {
                    statusClass = 'bg-green-100 text-green-800';
                    if (tagihan.status === 1) statusText = 'paid';
                } else if (tagihan.status === 'pending' || tagihan.status === 0) {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    if (tagihan.status === 0) statusText = 'pending';
                } else if (tagihan.status === 'failed' || tagihan.status === 'expired') {
                    statusClass = 'bg-red-100 text-red-800';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.user ? tagihan.user.name : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.nama_tagihan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formatRupiah(tagihan.total)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText || 'pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>${tagihan.created_at ? new Date(tagihan.created_at).toLocaleTimeString() : '-'}</div>
                        <div class="text-sm text-gray-500">${tagihan.created_at ? formatDate(tagihan.created_at) : '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewTagihanDetail(${tagihan.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Update pagination
            updatePaginationElements(response.pagination, loadTagihanPage);
            updateSortIndicators(sortBy, sortDirection);
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data tagihan', 'error');
        }
    }

    // Helper functions
    function formatRupiah(angka) {
        if (angka === null || angka === undefined) return '-';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }
    
    function formatDate(date) {
        if (!date) return '-';
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }
    
    async function viewTagihanDetail(id) {
        try {
            const response = await AwaitFetchApi(`admin/tagihan/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const tagihan = response.data;
                
                document.getElementById('detail-id').textContent = tagihan.id;
                document.getElementById('detail-nama').textContent = tagihan.nama_tagihan;
                
                const statusElement = document.getElementById('detail-status');
                statusElement.textContent = tagihan.status || 'pending';
                
                // Update status text for numeric values
                if (tagihan.status === 1) {
                    statusElement.textContent = 'paid';
                } else if (tagihan.status === 0) {
                    statusElement.textContent = 'pending';
                }
                
                // Update status badge color
                statusElement.className = 'px-2 py-1 text-xs rounded';
                if (tagihan.status === 'paid' || tagihan.status === 1) {
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                } else if (tagihan.status === 'pending' || tagihan.status === 0) {
                    statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
                } else if (tagihan.status === 'failed' || tagihan.status === 'expired') {
                    statusElement.classList.add('bg-red-100', 'text-red-800');
                } else {
                    statusElement.classList.add('bg-gray-100', 'text-gray-800');
                }
                
                document.getElementById('detail-total').textContent = formatRupiah(tagihan.total);
                document.getElementById('detail-user-id').textContent = tagihan.user_id;
                document.getElementById('detail-user-name').textContent = tagihan.user ? tagihan.user.name : '-';
                document.getElementById('detail-created').textContent = tagihan.created_at ? new Date(tagihan.created_at).toLocaleString() : '-';
                document.getElementById('detail-updated').textContent = tagihan.updated_at ? new Date(tagihan.updated_at).toLocaleString() : '-';
                
                // Add new fields
                document.getElementById('detail-va-number').textContent = tagihan.va_number || '-';
                document.getElementById('detail-transaction-qr-id').textContent = tagihan.transaction_qr_id || '-';
                document.getElementById('detail-created-time').textContent = tagihan.created_time || '-';
                document.getElementById('detail-qr-data').textContent = tagihan.qr_data || '-';
                
                openModal('detailModal');
            } else {
                showNotification(response.meta?.message || 'Gagal memuat detail tagihan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail tagihan', 'error');
        }
    }

    async function handleTagihanSubmit(e) {
        e.preventDefault();
        
        const user_id = document.getElementById('user_id').value;
        const nama_tagihan = document.getElementById('nama_tagihan').value;
        const total = document.getElementById('total').value;
        
        if (!user_id || !nama_tagihan || !total) {
            showNotification('Semua field harus diisi', 'error');
            return;
        }
        
        const data = {
            user_id: parseInt(user_id),
            nama_tagihan,
            total: parseInt(total)
        };
        
        try {
            const response = await AwaitFetchApi('admin/tagihan', 'POST', data);
            
            if (response.meta?.code === 201) {
                showNotification(response.meta.message || 'Tagihan berhasil dibuat', 'success');
                document.getElementById('tagihanModal').classList.add('hidden');
                loadTagihan();
            } else {
                showNotification(response.meta?.message || 'Gagal membuat tagihan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat membuat tagihan', 'error');
        }
    }

        // function closeModal() {
        //     document.getElementById('detailModal').classList.add('hidden');
        // }

    async function loadTrashData(page = 1) {
        try {
            const params = new URLSearchParams({
                page: page,
                sort_by: 'deleted_at',
                order_by: 'desc',
                per_page: 10
            });

            const response = await AwaitFetchApi(`admin/tagihans/trash?${params}`, 'GET');
            print.log('API Response - Tagihan Trash:', response);
            
            const tableBody = document.getElementById('trashTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data tagihan terhapus
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const tagihanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            tagihanList.forEach((tagihan) => {
                // Define status color based on status value
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = tagihan.status;
                
                if (tagihan.status === 'paid' || tagihan.status === 1) {
                    statusClass = 'bg-green-100 text-green-800';
                    if (tagihan.status === 1) statusText = 'paid';
                } else if (tagihan.status === 'pending' || tagihan.status === 0) {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    if (tagihan.status === 0) statusText = 'pending';
                } else if (tagihan.status === 'failed' || tagihan.status === 'expired') {
                    statusClass = 'bg-red-100 text-red-800';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.user ? tagihan.user.name : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${tagihan.nama_tagihan}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formatRupiah(tagihan.total)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText || 'pending'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${tagihan.deleted_at ? new Date(tagihan.deleted_at).toLocaleString() : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-green-600 hover:text-green-900" onclick="restoreTagihan(${tagihan.id})">
                            <i class="fas fa-trash-restore"></i> Restore
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Update pagination
            updateTrashPagination(response.pagination);
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data tagihan terhapus', 'error');
        }
    }
    
    function updateTrashPagination(pagination) {
        // Default values in case pagination data is missing or malformed
        let currentPageValue = 1;
        let totalPagesValue = 1;
        let totalItemsValue = 0;
        let perPageValue = 10;
        
        // Only update values if pagination data exists and is valid
        if (pagination && typeof pagination === 'object') {
            currentPageValue = parseInt(pagination.page) || 1;
            totalPagesValue = parseInt(pagination.total_pages) || 1;
            totalItemsValue = parseInt(pagination.total_items) || 0;
            perPageValue = parseInt(pagination.per_page) || 10;
            
            // Update global variables
            trashCurrentPage = currentPageValue;
            trashTotalPages = totalPagesValue;
        }
        
        // Calculate start and end values, protecting against NaN
        const start = totalItemsValue > 0 ? (currentPageValue - 1) * perPageValue + 1 : 0;
        const end = Math.min(start + perPageValue - 1, totalItemsValue);
        
        // Update DOM elements
        document.getElementById('trash-pagination-start').textContent = start;
        document.getElementById('trash-pagination-end').textContent = end;
        document.getElementById('trash-pagination-total').textContent = totalItemsValue;
        
        // Update previous and next buttons state
        document.getElementById('trash-prev-page').disabled = currentPageValue <= 1;
        document.getElementById('trash-next-page').disabled = currentPageValue >= totalPagesValue;
        
        // Generate page numbers
        const pageNumbers = document.getElementById('trash-page-numbers');
        pageNumbers.innerHTML = '';
        
        // Don't show page numbers if there are no items
        if (totalItemsValue === 0) {
            return;
        }
        
        // Determine page range to display
        const maxPageButtons = 5;
        let startPage = Math.max(1, currentPageValue - Math.floor(maxPageButtons / 2));
        let endPage = Math.min(totalPagesValue, startPage + maxPageButtons - 1);
        
        if (endPage - startPage + 1 < maxPageButtons) {
            startPage = Math.max(1, endPage - maxPageButtons + 1);
        }
        
        // Add first page button if not at the beginning
        if (startPage > 1) {
            const firstPageBtn = document.createElement('button');
            firstPageBtn.className = 'px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
            firstPageBtn.textContent = '1';
            firstPageBtn.onclick = () => loadTrashData(1);
            pageNumbers.appendChild(firstPageBtn);
            
            if (startPage > 2) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-3 py-1 text-gray-500';
                ellipsis.textContent = '...';
                pageNumbers.appendChild(ellipsis);
            }
        }
        
        // Add page number buttons
        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `px-3 py-1 rounded border ${currentPageValue === i ? 'bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}`;
            pageBtn.textContent = i;
            pageBtn.onclick = () => loadTrashData(i);
            pageNumbers.appendChild(pageBtn);
        }
        
        // Add last page button if not at the end
        if (endPage < totalPagesValue) {
            if (endPage < totalPagesValue - 1) {
                const ellipsis = document.createElement('span');
                ellipsis.className = 'px-3 py-1 text-gray-500';
                ellipsis.textContent = '...';
                pageNumbers.appendChild(ellipsis);
            }
            
            const lastPageBtn = document.createElement('button');
            lastPageBtn.className = 'px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
            lastPageBtn.textContent = totalPagesValue;
            lastPageBtn.onclick = () => loadTrashData(totalPagesValue);
            pageNumbers.appendChild(lastPageBtn);
        }
    }
    
    async function restoreTagihan(id) {
        try {
            const result = await showDeleteConfirmation(
                'Apakah Anda yakin ingin memulihkan tagihan ini?', 
                'Ya, Pulihkan', 
                'Batal'
            );
            
            if (!result.isConfirmed) {
                return;
            }
            
            const response = await AwaitFetchApi(`admin/tagihan/${id}/restore`, 'PUT');
            
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Tagihan berhasil dipulihkan', 'success');
                loadTrashData(trashCurrentPage);
                loadTagihan(currentPage);
            } else {
                showNotification(response.meta?.message || 'Gagal memulihkan tagihan', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memulihkan tagihan', 'error');
        }
    }
// openModal('trashModal');
    function openTrashModal() {
        loadTrashData();
        openModal('trashModal');
    }
    
    // function closeTrashModal() {
    //     document.getElementById('trashModal').classList.add('hidden');
    // }
</script>
@endsection 
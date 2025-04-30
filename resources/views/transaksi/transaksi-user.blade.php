@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Transaksi</h1>
        <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="openTrashModal()">
            <i class="fas fa-trash mr-2"></i> Trash
        </button>
    </div>
    
    <!-- Search and Filter Controls -->
    <x-filter resetFunction="resetFilters">
        <x-filter-text 
            id="searchInput" 
            label="Search" 
            placeholder="Cari transaksi..." 
            onChangeFunction="updateSearchFilter" />
        
        <x-filter-select 
            id="statusFilter" 
            label="Status" 
            :options="[''=>'All Status', 'pending'=>'Pending', 'success'=>'Success', 'failed'=>'Failed']" 
            onChangeFunction="updateStatusFilter" />
        
        <x-filter-date-range 
            startId="startDate" 
            endId="endDate" 
            label="Date Range" 
            onStartChangeFunction="updateStartDateFilter" 
            onEndChangeFunction="updateEndDateFilter" />
    </x-filter>
    
    <!-- Transaksi Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <x-sortable-header column="id" label="ID" sortFunction="handleSort" />
                    <x-sortable-header column="user" label="User" sortFunction="handleSort" />
                    <x-sortable-header column="tagihan" label="Tagihan" sortFunction="handleSort" />
                    <x-sortable-header column="total" label="Total" sortFunction="handleSort" />
                    <x-sortable-header column="status" label="Status" sortFunction="handleSort" />
                    <x-sortable-header column="method" label="Metode" sortFunction="handleSort" />
                    <x-sortable-header column="va" label="VA/QR" sortFunction="handleSort" />
                    <x-sortable-header column="created_at" label="Waktu" sortFunction="handleSort" />
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="transaksiTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        <!-- Pagination -->
        <x-pagination id="transaksiPagination" loadFunction="loadTransaksiPage" />
    </div>
</div>

<!-- Modal Detail Transaksi -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Transaksi</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold mb-4">Informasi Transaksi</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID Transaksi</label>
                        <p class="mt-1" id="detail-id">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ref. Number</label>
                        <p class="mt-1" id="detail-ref">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800" id="detail-status">-</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pembayaran</label>
                        <p class="mt-1 text-lg font-semibold" id="detail-total">-</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">Detail Pembayaran</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="mt-1" id="detail-method">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor VA</label>
                        <p class="mt-1" id="detail-va">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">QR ID</label>
                        <p class="mt-1" id="detail-qr">-</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Transaksi</label>
                        <p class="mt-1" id="detail-time">-</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <h4 class="font-semibold mb-4">Informasi Tambahan</h4>
            <div class="grid grid-cols-3 gap-4 text-sm text-gray-500">
                <div>
                    <span>Dibuat pada:</span>
                    <p id="detail-created" class="font-medium">-</p>
                </div>
                <div>
                    <span>Terakhir diupdate:</span>
                    <p id="detail-updated" class="font-medium">-</p>
                </div>
                <div>
                    <span>User ID:</span>
                    <p id="detail-userid" class="font-medium">-</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Trash Transaksi -->
<div id="trashModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Transaksi Terhapus</h3>
            <button onclick="closeModal('trashModal')" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-white rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
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
        loadTransaksi();
        
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
        loadTransaksi();
    }
    
    function updateStatusFilter(value) {
        filters.status = value;
        loadTransaksi();
    }
    
    function updateStartDateFilter(value) {
        filters.start_date = value;
        loadTransaksi();
    }
    
    function updateEndDateFilter(value) {
        filters.end_date = value;
        loadTransaksi();
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
        loadTransaksi();
    }
    
    // Sort function
    function handleSort(column) {
        handleSortGeneric(column, loadTransaksi);
    }
    
    // Pagination function
    function loadTransaksiPage(page) {
        currentPage = page;
        loadTransaksi();
    }

    // Main data loading function
    async function loadTransaksi() {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                sort_by: sortBy,
                order_by: sortDirection,
                ...filters
            });

            const response = await AwaitFetchApi(`admin/transaksi?${params}`, 'GET');
            
            const tableBody = document.getElementById('transaksiTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data transaksi
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const transaksiList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            transaksiList.forEach((transaksi) => {
                // Define status color based on status value
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = transaksi.status;
                
                if (transaksi.status === 'success' || transaksi.status === 'completed' || transaksi.status === '1') {
                    statusClass = 'bg-green-100 text-green-800';
                    if (transaksi.status === '1') statusText = 'success';
                } else if (transaksi.status === 'pending' || transaksi.status === '0') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    if (transaksi.status === '0') statusText = 'pending';
                } else if (transaksi.status === 'failed' || transaksi.status === 'canceled') {
                    statusClass = 'bg-red-100 text-red-800';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${transaksi.user ? 
                          `<div class="font-medium">${transaksi.user.peserta ? transaksi.user.peserta.nama : '-'}</div>
                           <div class="text-xs text-gray-500">ID: ${transaksi.user.id || '-'}</div>
                           <div class="text-xs text-gray-500">${transaksi.user.no_telp || '-'}</div>` 
                          : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.tagihan ? transaksi.tagihan.nama_tagihan : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formatRupiah(transaksi.total)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.method || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.va_number || transaksi.transaction_qr_id || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>${transaksi.created_time ? new Date(transaksi.created_time).toLocaleTimeString() : '-'}</div>
                        <div class="text-sm text-gray-500">${transaksi.created_time ? formatDate(transaksi.created_time) : '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewTransaksiDetail(${transaksi.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Update pagination
            updatePaginationElements(response.pagination, loadTransaksiPage);
            updateSortIndicators(sortBy, sortDirection);
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data transaksi', 'error');
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
    
    async function viewTransaksiDetail(id) {
        try {
            const response = await AwaitFetchApi(`admin/transaksi/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const transaksi = response.data;
                
                document.getElementById('detail-id').textContent = transaksi.id;
                document.getElementById('detail-ref').textContent = transaksi.ref_no || '-';
                
                const statusElement = document.getElementById('detail-status');
                statusElement.textContent = transaksi.status || '-';
                
                // Update status text for numeric values
                if (transaksi.status === '1') {
                    statusElement.textContent = 'success';
                } else if (transaksi.status === '0') {
                    statusElement.textContent = 'pending';
                }
                
                // Update status badge color
                statusElement.className = 'px-2 py-1 text-xs rounded';
                if (transaksi.status === 'success' || transaksi.status === 'completed' || transaksi.status === '1') {
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                } else if (transaksi.status === 'pending' || transaksi.status === '0') {
                    statusElement.classList.add('bg-yellow-100', 'text-yellow-800');
                } else if (transaksi.status === 'failed' || transaksi.status === 'canceled') {
                    statusElement.classList.add('bg-red-100', 'text-red-800');
                } else {
                    statusElement.classList.add('bg-gray-100', 'text-gray-800');
                }
                
                document.getElementById('detail-total').textContent = formatRupiah(transaksi.total);
                document.getElementById('detail-method').textContent = transaksi.method || '-';
                document.getElementById('detail-va').textContent = transaksi.va_number || '-';
                document.getElementById('detail-qr').textContent = transaksi.transaction_qr_id || '-';
                document.getElementById('detail-time').textContent = transaksi.created_time ? new Date(transaksi.created_time).toLocaleString() : '-';
                
                document.getElementById('detail-created').textContent = transaksi.created_at ? new Date(transaksi.created_at).toLocaleString() : '-';
                document.getElementById('detail-updated').textContent = transaksi.updated_at ? new Date(transaksi.updated_at).toLocaleString() : '-';
                document.getElementById('detail-userid').textContent = transaksi.user_id || (transaksi.user ? transaksi.user.id : '-');
                
                // Add peserta name to the modal
                const detailInfo = document.querySelector('.mt-6 .grid');
                if (!document.getElementById('detail-peserta-name')) {
                    const pesertaDiv = document.createElement('div');
                    pesertaDiv.innerHTML = `
                        <span>Nama Peserta:</span>
                        <p id="detail-peserta-name" class="font-medium">-</p>
                    `;
                    detailInfo.insertBefore(pesertaDiv, detailInfo.firstChild);
                }
                document.getElementById('detail-peserta-name').textContent = 
                    transaksi.user && transaksi.user.peserta ? transaksi.user.peserta.nama : '-';
                
                // Add phone number to the modal
                if (!document.getElementById('detail-phone')) {
                    const phoneDiv = document.createElement('div');
                    phoneDiv.innerHTML = `
                        <span>Phone Number:</span>
                        <p id="detail-phone" class="font-medium">-</p>
                    `;
                    detailInfo.appendChild(phoneDiv);
                }
                document.getElementById('detail-phone').textContent = transaksi.user && transaksi.user.no_telp ? transaksi.user.no_telp : '-';
                
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                showNotification(response.meta?.message || 'Gagal memuat detail transaksi', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail transaksi', 'error');
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    async function loadTrashData(page = 1) {
        try {
            const params = new URLSearchParams({
                page: page,
                sort_by: 'deleted_at',
                order_by: 'desc',
                per_page: 10
            });

            const response = await AwaitFetchApi(`admin/transaksis/trash?${params}`, 'GET');
            print.log('API Response - Transaksi Trash:', response);
            
            const tableBody = document.getElementById('trashTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data transaksi terhapus
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const transaksiList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            transaksiList.forEach((transaksi) => {
                // Define status color based on status value
                let statusClass = 'bg-gray-100 text-gray-800';
                let statusText = transaksi.status;
                
                if (transaksi.status === 'success' || transaksi.status === 'completed' || transaksi.status === '1') {
                    statusClass = 'bg-green-100 text-green-800';
                    if (transaksi.status === '1') statusText = 'success';
                } else if (transaksi.status === 'pending' || transaksi.status === '0') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
                    if (transaksi.status === '0') statusText = 'pending';
                } else if (transaksi.status === 'failed' || transaksi.status === 'canceled') {
                    statusClass = 'bg-red-100 text-red-800';
                }
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${transaksi.user ? 
                          `<div class="font-medium">${transaksi.user.peserta ? transaksi.user.peserta.nama : '-'}</div>
                           <div class="text-xs text-gray-500">ID: ${transaksi.user.id || '-'}</div>` 
                          : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.tagihan ? transaksi.tagihan.nama_tagihan : '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formatRupiah(transaksi.total)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.method || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${transaksi.deleted_at ? new Date(transaksi.deleted_at).toLocaleString() : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-green-600 hover:text-green-900" onclick="restoreTransaksi(${transaksi.id})">
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
            showNotification('Terjadi kesalahan saat memuat data transaksi terhapus', 'error');
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
    
    async function restoreTransaksi(id) {
        try {
            const result = await showDeleteConfirmation(
                'Apakah Anda yakin ingin memulihkan transaksi ini?', 
                'Ya, Pulihkan', 
                'Batal'
            );
            
            if (!result.isConfirmed) {
                return;
            }
            
            const response = await AwaitFetchApi(`admin/transaksi/${id}/restore`, 'PUT');
            
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Transaksi berhasil dipulihkan', 'success');
                loadTrashData(trashCurrentPage);
                loadTransaksi(currentPage);
            } else {
                showNotification(response.meta?.message || 'Gagal memulihkan transaksi', 'error');
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memulihkan transaksi', 'error');
        }
    }

    function openTrashModal() {
        loadTrashData();
        openModal('trashModal');
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection
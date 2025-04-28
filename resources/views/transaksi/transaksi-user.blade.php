@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Transaksi</h1>
    
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

    // Initialize the page
    document.addEventListener('DOMContentLoaded', () => {
        loadTransaksi();
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
                if (transaksi.status === 'success' || transaksi.status === 'completed') {
                    statusClass = 'bg-green-100 text-green-800';
                } else if (transaksi.status === 'pending') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
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
                            ${transaksi.status || '-'}
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
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data transaksi', 'error');
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
                
                // Update status badge color
                statusElement.className = 'px-2 py-1 text-xs rounded';
                if (transaksi.status === 'success' || transaksi.status === 'completed') {
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                } else if (transaksi.status === 'pending') {
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
                showAlert(response.meta?.message || 'Gagal memuat detail transaksi', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail transaksi', 'error');
        }
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function showAlert(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: type,
                title: message,
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            alert(message);
        }
    }
</script>
@endsection
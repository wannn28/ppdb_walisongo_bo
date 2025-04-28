@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Tagihan</h1>
    
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
    <div class="bg-white rounded-lg shadow-md overflow-hidden mt-4">
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
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
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
                if (tagihan.status === 'paid') {
                    statusClass = 'bg-green-100 text-green-800';
                } else if (tagihan.status === 'pending') {
                    statusClass = 'bg-yellow-100 text-yellow-800';
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
                            ${tagihan.status || 'pending'}
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
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data tagihan', 'error');
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
                
                // Update status badge color
                statusElement.className = 'px-2 py-1 text-xs rounded';
                if (tagihan.status === 'paid') {
                    statusElement.classList.add('bg-green-100', 'text-green-800');
                } else if (tagihan.status === 'pending') {
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
                
                document.getElementById('detailModal').classList.remove('hidden');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail tagihan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail tagihan', 'error');
        }
    }

    async function handleTagihanSubmit(e) {
        e.preventDefault();
        
        const user_id = document.getElementById('user_id').value;
        const nama_tagihan = document.getElementById('nama_tagihan').value;
        const total = document.getElementById('total').value;
        
        if (!user_id || !nama_tagihan || !total) {
            showAlert('Semua field harus diisi', 'error');
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
                showAlert(response.meta.message || 'Tagihan berhasil dibuat', 'success');
                document.getElementById('tagihanModal').classList.add('hidden');
                loadTagihan();
            } else {
                showAlert(response.meta?.message || 'Gagal membuat tagihan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat membuat tagihan', 'error');
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
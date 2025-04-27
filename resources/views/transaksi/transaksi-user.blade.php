@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Transaksi</h1>
        <div>
            <button id="btnAddTagihan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                <i class="fas fa-plus mr-2"></i> Tambah Tagihan
            </button>
        </div>
    </div>
    
    <!-- Tab navigation -->
    <div class="mb-4 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button class="tab-button inline-block p-4 border-b-2 border-blue-500 rounded-t-lg active" data-target="transaksiTab">
                    Transaksi
                </button>
            </li>
            <li class="mr-2">
                <button class="tab-button inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" data-target="tagihanTab">
                    Tagihan
                </button>
            </li>
        </ul>
    </div>
    
    <!-- Transaksi Tab -->
    <div id="transaksiTab" class="tab-content bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tagihan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">VA/QR</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="transaksiTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>
    
    <!-- Tagihan Tab -->
    <div id="tagihanTab" class="tab-content bg-white rounded-lg shadow-md overflow-hidden hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="tagihanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
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

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Load data
        loadTransaksi();
        loadTagihan();
        
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
            });
        });

        // Event listeners for modals
        document.getElementById('btnAddTagihan').addEventListener('click', () => {
            document.getElementById('tagihanForm').reset();
            openModal('tagihanModal');
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('tagihanForm').addEventListener('submit', handleTagihanSubmit);
    });

    async function loadTransaksi() {
        try {
            const response = await AwaitFetchApi('admin/transaksi', 'GET');
            console.log('API Response - Transaksi:', response);
            
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
                    <td class="px-6 py-4 whitespace-nowrap">${transaksi.user ? transaksi.user.name : '-'}</td>
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
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data transaksi', 'error');
        }
    }
    
    async function loadTagihan() {
        try {
            const response = await AwaitFetchApi('admin/tagihan', 'GET');
            console.log('API Response - Tagihan:', response);
            
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
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat data tagihan', 'error');
        }
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
                document.getElementById('detail-userid').textContent = transaksi.user_id;
                
                openModal('detailModal');
            } else {
                showAlert(response.meta?.message || 'Gagal memuat detail transaksi', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat memuat detail transaksi', 'error');
        }
    }
    
    async function viewTagihanDetail(id) {
        try {
            const response = await AwaitFetchApi(`admin/tagihan/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const tagihan = response.data;
                
                document.getElementById('detailModal').querySelector('h3').textContent = 'Detail Tagihan';
                document.getElementById('detail-id').textContent = tagihan.id;
                document.getElementById('detail-ref').textContent = '-';
                
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
                document.getElementById('detail-method').textContent = '-';
                document.getElementById('detail-va').textContent = tagihan.va_number || '-';
                document.getElementById('detail-qr').textContent = tagihan.transaction_qr_id || '-';
                document.getElementById('detail-time').textContent = tagihan.created_time ? new Date(tagihan.created_time).toLocaleString() : '-';
                
                document.getElementById('detail-created').textContent = tagihan.created_at ? new Date(tagihan.created_at).toLocaleString() : '-';
                document.getElementById('detail-updated').textContent = tagihan.updated_at ? new Date(tagihan.updated_at).toLocaleString() : '-';
                document.getElementById('detail-userid').textContent = tagihan.user_id;
                
                openModal('detailModal');
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
                closeModal('tagihanModal');
                loadTagihan();
            } else {
                showAlert(response.meta?.message || 'Gagal membuat tagihan', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('Terjadi kesalahan saat membuat tagihan', 'error');
        }
    }
    
    function viewDetail(id) {
        document.getElementById('detailModal').classList.remove('hidden');
        // Implementasi untuk mengambil dan menampilkan detail transaksi
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
    
    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
    
    // Format currency
    function formatRupiah(angka) {
        if (angka === null || angka === undefined) return '-';
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }
    
    // Format date
    function formatDate(date) {
        if (!date) return '-';
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
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
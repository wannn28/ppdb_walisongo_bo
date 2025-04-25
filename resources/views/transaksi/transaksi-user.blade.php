@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Transaksi</h1>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
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
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">1</td>
                    <td class="px-6 py-4 whitespace-nowrap">John Doe</td>
                    <td class="px-6 py-4 whitespace-nowrap">SPP Agustus 2023</td>
                    <td class="px-6 py-4 whitespace-nowrap">Rp 500.000</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Sukses
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">VA BNI</td>
                    <td class="px-6 py-4 whitespace-nowrap">88812345678</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>15:30:00</div>
                        <div class="text-sm text-gray-500">15 Agt 2023</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewDetail(1)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
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
                        <p class="mt-1" id="detail-id">TR-001</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ref. Number</label>
                        <p class="mt-1" id="detail-ref">REF12345</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800" id="detail-status">Sukses</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Pembayaran</label>
                        <p class="mt-1 text-lg font-semibold" id="detail-total">Rp 500.000</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">Detail Pembayaran</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                        <p class="mt-1" id="detail-method">Virtual Account BNI</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor VA/QR</label>
                        <p class="mt-1" id="detail-va">88812345678</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">QR ID</label>
                        <p class="mt-1" id="detail-qr">QR123456</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Transaksi</label>
                        <p class="mt-1" id="detail-time">15:30:00</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <h4 class="font-semibold mb-4">Informasi Tambahan</h4>
            <div class="grid grid-cols-3 gap-4 text-sm text-gray-500">
                <div>
                    <span>Dibuat pada:</span>
                    <p id="detail-created" class="font-medium">15 Agustus 2023 15:30:00</p>
                </div>
                <div>
                    <span>Terakhir diupdate:</span>
                    <p id="detail-updated" class="font-medium">15 Agustus 2023 15:35:00</p>
                </div>
                <div>
                    <span>User ID:</span>
                    <p id="detail-userid" class="font-medium">USR001</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewDetail(id) {
    document.getElementById('detailModal').classList.remove('hidden');
    // Implementasi untuk mengambil dan menampilkan detail transaksi
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Format currency
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(angka);
}

// Format date
function formatDate(date) {
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Detail Peserta PPDB</h1>
        <div class="flex gap-4">
            <input type="text" id="searchInput" placeholder="Cari NIS/Nama..." class="border rounded-lg px-4 py-2 w-64">
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="openTambahModal()">
                <i class="fas fa-plus mr-2"></i> Tambah Peserta
            </button>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">TTL</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kontak</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">1</td>
                    <td class="px-6 py-4 whitespace-nowrap">1234567890</td>
                    <td class="px-6 py-4 whitespace-nowrap">2021001</td>
                    <td class="px-6 py-4 whitespace-nowrap">Ahmad Fajar</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>Surabaya</div>
                        <div class="text-sm text-gray-500">15 Agustus 2005</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">Laki-laki</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>081234567890</div>
                        <div class="text-sm text-gray-500">ahmad@email.com</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">SMA</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewDetail(1)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" onclick="deletePeserta(1)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <!-- Pagination Component -->
        <div class="px-6 py-4 bg-white border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Menampilkan <span id="pagination-start">1</span> sampai <span id="pagination-end">10</span> dari <span id="pagination-total">0</span> data
                </div>
                <div class="flex space-x-2">
                    <button id="prev-page" class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-chevron-left mr-1"></i> Sebelumnya
                    </button>
                    <div id="page-numbers" class="flex space-x-1">
                        <!-- Page numbers will be inserted here -->
                    </div>
                    <button id="next-page" class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Selanjutnya <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Tambah Peserta -->
<div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Peserta Baru</h3>
            <form id="pesertaForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">NISN</label>
                        <input type="text" name="nisn" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeTambahModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
<!-- Modal Detail Peserta -->
<div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-2/3 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Peserta PPDB</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold mb-4">Data Pribadi</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NISN</label>
                        <p class="mt-1" id="detail-nisn">1234567890</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIS</label>
                        <p class="mt-1" id="detail-nis">2021001</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <p class="mt-1" id="detail-nama">Ahmad Fajar</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tempat, Tanggal Lahir</label>
                        <p class="mt-1" id="detail-ttl">Surabaya, 15 Agustus 2005</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <p class="mt-1" id="detail-gender">Laki-laki</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Agama</label>
                        <p class="mt-1" id="detail-agama">Islam</p>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-semibold mb-4">Informasi Kontak & Pendidikan</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1" id="detail-email">ahmad@email.com</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                        <p class="mt-1" id="detail-telp">081234567890</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <p class="mt-1" id="detail-alamat">Jl. Contoh No. 123, Surabaya</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenjang Sekolah</label>
                        <p class="mt-1" id="detail-jenjang">SMA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pilihan Jurusan 1</label>
                        <p class="mt-1" id="detail-jurusan1">IPA</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pilihan Jurusan 2</label>
                        <p class="mt-1" id="detail-jurusan2">IPS</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <h4 class="font-semibold mb-4">Informasi Tambahan</h4>
            <div class="grid grid-cols-3 gap-4 text-sm text-gray-500">
                <div>
                    <span>Terdaftar pada:</span>
                    <p id="detail-created" class="font-medium">15 Agustus 2023 10:30:00</p>
                </div>
                <div>
                    <span>Terakhir diupdate:</span>
                    <p id="detail-updated" class="font-medium">15 Agustus 2023 14:20:00</p>
                </div>
                <div>
                    <span>Status:</span>
                    <p id="detail-status" class="font-medium text-green-600">Aktif</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let totalPages = 1;

document.addEventListener('DOMContentLoaded', () => {
    loadPesertaData();
    
    // Setup pagination event listeners
    document.getElementById('prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            loadPesertaData(currentPage - 1);
        }
    });
    
    document.getElementById('next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
            loadPesertaData(currentPage + 1);
        }
    });
});

async function loadPesertaData(page = 1) {
    try {
        const response = await AwaitFetchApi(`admin/pesertas?page=${page}`, 'GET');
        if(response?.data) {
            renderPesertaTable(response.data);
            updatePagination(response.pagination);
        }
    } catch (error) {
        showAlert('Gagal memuat data peserta', 'error');
    }
}

function renderPesertaTable(pesertas) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';
    
    pesertas.forEach((peserta, index) => {
        const row = `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap">${peserta.nisn || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${peserta.nis || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${peserta.nama}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>${peserta.tempat_lahir}</div>
                    <div class="text-sm text-gray-500">${new Date(peserta.tanggal_lahir).toLocaleDateString()}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded ${peserta.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">
                        ${peserta.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div>${peserta.no_telp}</div>
                    <div class="text-sm text-gray-500">${peserta.user?.email || '-'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">${peserta.jenjang_sekolah}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="viewDetail(${peserta.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="text-red-600 hover:text-red-900" onclick="deletePeserta(${peserta.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

async function viewDetail(id) {
    try {
        const response = await AwaitFetchApi(`admin/peserta/${id}`, 'GET');
        if(response?.data) {
            const p = response.data;
            document.getElementById('detail-nisn').textContent = p.nisn;
            document.getElementById('detail-nis').textContent = p.nis;
            document.getElementById('detail-nama').textContent = p.nama;
            document.getElementById('detail-ttl').textContent = `${p.tempat_lahir}, ${new Date(p.tanggal_lahir).toLocaleDateString()}`;
            document.getElementById('detail-gender').textContent = p.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
            document.getElementById('detail-email').textContent = p.user?.email || '-';
            document.getElementById('detail-telp').textContent = p.no_telp;
            document.getElementById('detail-alamat').textContent = p.alamat;
            document.getElementById('detail-jenjang').textContent = p.jenjang_sekolah;
            document.getElementById('detail-jurusan1').textContent = p.jurusan1?.nama || '-';
            document.getElementById('detail-jurusan2').textContent = p.jurusan2?.nama || '-';
            document.getElementById('detail-created').textContent = new Date(p.created_at).toLocaleString();
            document.getElementById('detail-updated').textContent = new Date(p.updated_at).toLocaleString();
            document.getElementById('detailModal').classList.remove('hidden');
        }
    } catch (error) {
        showAlert('Gagal memuat detail peserta', 'error');
    }
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

async function deletePeserta(id) {
    const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus data peserta ini?');
    if (result.isConfirmed) {
        try {
            const response = await AwaitFetchApi(`admin/peserta/${id}`, 'DELETE');
            if(response.meta?.code === 200) {
                showAlert('Peserta berhasil dihapus', 'success');
                loadPesertaData();
            }
        } catch (error) {
            showAlert('Gagal menghapus peserta', 'error');
        }
    }
}

function updatePagination(pagination) {
    currentPage = pagination.page;
    totalPages = pagination.total_pages;
    
    // Update pagination info
    const startItem = (pagination.page - 1) * pagination.per_page + 1;
    const endItem = Math.min(pagination.page * pagination.per_page, pagination.total_items);
    
    document.getElementById('pagination-start').textContent = startItem;
    document.getElementById('pagination-end').textContent = endItem;
    document.getElementById('pagination-total').textContent = pagination.total_items;
    
    // Update pagination buttons state
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');
    
    prevButton.disabled = pagination.page <= 1;
    nextButton.disabled = pagination.page >= pagination.total_pages;
    
    // Generate page number buttons
    const pageNumbersContainer = document.getElementById('page-numbers');
    pageNumbersContainer.innerHTML = '';
    
    // Determine which page numbers to show
    let startPage = Math.max(1, pagination.page - 2);
    let endPage = Math.min(pagination.total_pages, pagination.page + 2);
    
    // Ensure we always show 5 page numbers if possible
    if (endPage - startPage < 4 && pagination.total_pages > 4) {
        if (startPage === 1) {
            endPage = Math.min(5, pagination.total_pages);
        } else if (endPage === pagination.total_pages) {
            startPage = Math.max(1, pagination.total_pages - 4);
        }
    }
    
    // Add first page button if not included in range
    if (startPage > 1) {
        const firstPageBtn = createPageButton(1, pagination.page === 1);
        pageNumbersContainer.appendChild(firstPageBtn);
        
        if (startPage > 2) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'px-3 py-1 text-gray-500';
            ellipsis.textContent = '...';
            pageNumbersContainer.appendChild(ellipsis);
        }
    }
    
    // Add page number buttons
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = createPageButton(i, pagination.page === i);
        pageNumbersContainer.appendChild(pageBtn);
    }
    
    // Add last page button if not included in range
    if (endPage < pagination.total_pages) {
        if (endPage < pagination.total_pages - 1) {
            const ellipsis = document.createElement('span');
            ellipsis.className = 'px-3 py-1 text-gray-500';
            ellipsis.textContent = '...';
            pageNumbersContainer.appendChild(ellipsis);
        }
        
        const lastPageBtn = createPageButton(pagination.total_pages, pagination.page === pagination.total_pages);
        pageNumbersContainer.appendChild(lastPageBtn);
    }
}

function createPageButton(pageNumber, isActive) {
    const button = document.createElement('button');
    button.textContent = pageNumber;
    button.className = isActive 
        ? 'px-3 py-1 rounded bg-blue-500 text-white' 
        : 'px-3 py-1 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-50';
    
    if (!isActive) {
        button.addEventListener('click', () => loadPesertaData(pageNumber));
    }
    
    return button;
}

// Fungsi Search
document.getElementById('searchInput').addEventListener('input', function(e) {
    const term = e.target.value.toLowerCase();
    const filtered = allPeserta.filter(p => 
        p.nama.toLowerCase().includes(term) || 
        (p.nisn && p.nisn.toString().includes(term)) ||
        (p.nis && p.nis.toString().includes(term))
    );
    renderPesertaTable(filtered);
});

// Fungsi Tambah Peserta
let allPeserta = [];

async function savePeserta(formData) {
    try {
        const response = await AwaitFetchApi('admin/peserta', 'POST', formData);
        if(response.meta?.code === 201) {
            showAlert('Peserta berhasil ditambahkan', 'success');
            closeTambahModal();
            loadPesertaData();
            return true;
        }
        return false;
    } catch (error) {
        showAlert('Gagal menambahkan peserta', 'error');
        return false;
    }
}

function openTambahModal() {
    document.getElementById('tambahModal').classList.remove('hidden');
    document.getElementById('pesertaForm').reset();
}

function closeTambahModal() {
    document.getElementById('tambahModal').classList.add('hidden');
}

document.getElementById('pesertaForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = {
        nama: this.nama.value,
        nisn: this.nisn.value,
        jenis_kelamin: this.jenis_kelamin.value,
        no_telp: this.no_telp?.value,
        tempat_lahir: this.tempat_lahir?.value,
        tanggal_lahir: this.tanggal_lahir?.value,
        alamat: this.alamat?.value,
        jenjang_sekolah: this.jenjang_sekolah?.value
    };
    
    await savePeserta(formData);
});
</script>
@endsection
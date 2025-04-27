@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Berkas Peserta</h1>
            <div class="flex gap-4">
                <input type="text" id="searchInput" placeholder="Cari berkas..." class="border rounded-lg px-4 py-2 w-64">
                <button onclick="searchBerkas()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('id')">
                            ID <span id="sort-id"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('peserta_id')">
                            ID Peserta <span id="sort-peserta_id"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('ketentuan_berkas_id')">
                            Ketentuan Berkas <span id="sort-ketentuan_berkas_id"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('nama_file')">
                            Nama File <span id="sort-nama_file"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Preview
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('created_at')">
                            Tanggal Upload <span id="sort-created_at"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" onclick="handleSort('updated_at')">
                            Terakhir Update <span id="sort-updated_at"></span>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="berkasTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan dimasukkan lewat JS -->
                </tbody>
            </table>
            <div class="px-6 py-4">
                <div id="pagination" class="flex justify-between items-center gap-2 mt-4">
                    <!-- Tombol pagination akan di-generate lewat JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview File -->
    <div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Preview Berkas</h3>
                <button data-close-modal="previewModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                <iframe id="filePreview" class="w-full h-full rounded-lg"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Berkas -->
    <div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Alasan Penolakan</h3>
                <form id="tolakForm">
                    <div class="mb-4">
                        <textarea id="alasanTolak" name="alasanTolak" rows="4"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" data-close-modal="tolakModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                            Tolak Berkas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        let sortBy = 'id';
        let sortDirection = 'asc';
        let currentPage = 1;
        let perPage = 10;
        let searchTerm = '';

        document.addEventListener('DOMContentLoaded', () => {
            loadBerkasPeserta(1); // mulai dari halaman 1
            
            // Add event listener for search input
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchBerkas();
                }
            });
        });

        async function loadBerkasPeserta(page = 1) {
            try {
                const url = `admin/berkas?page=${page}&per_page=${perPage}&sort_by=${sortBy}&order_by=${sortDirection}${searchTerm ? '&search=' + searchTerm : ''}`;
                const response = await AwaitFetchApi(url, 'GET');
                const tbody = document.getElementById('berkasTableBody');
                tbody.innerHTML = ''; // Kosongkan isi tabel

                if (response && response.data) {
                    if (response.data.length === 0) {
                        // Show empty state message
                        const emptyRow = document.createElement('tr');
                        emptyRow.innerHTML = `
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data berkas yang ditemukan
                            </td>
                        `;
                        tbody.appendChild(emptyRow);
                    } else {
                        response.data.forEach((berkas, index) => {
                            const peserta = berkas.peserta || {}; // Add null check with default empty object
                            const ketentuan = berkas.ketentuan_berkas;

                            const row = document.createElement('tr');
                            row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">${berkas.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${peserta.id ? peserta.id : 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${ketentuan?.nama ?? '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${berkas.nama_file}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="#" class="text-blue-600 hover:text-blue-900" onclick="previewFile('${berkas.url_file.replace(/`/g, '').trim()}')">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">${new Date(berkas.created_at).toLocaleString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${new Date(berkas.updated_at).toLocaleString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-red-600 hover:text-red-900" onclick="deleteBerkas(${berkas.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                            `;
                            tbody.appendChild(row);
                        });
                    }

                    // Render pagination and update sort indicators
                    renderPagination(response.pagination);
                    updateSortIndicators();
                }
            } catch (error) {
                console.error('Error loading berkas:', error);
                showAlert('Terjadi kesalahan saat memuat data berkas', 'error');
            }
        }

        function handleSort(column) {
            if (sortBy === column) {
                // Toggle direction if same column
                sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                // Default to ascending for new column
                sortBy = column;
                sortDirection = 'asc';
            }
            
            currentPage = 1; // Reset to first page when sorting
            loadBerkasPeserta(currentPage);
        }

        function updateSortIndicators() {
            // Clear all sort indicators
            document.querySelectorAll('[id^="sort-"]').forEach(el => {
                el.innerHTML = '';
            });
            
            // Set indicator for current sort column
            const indicator = sortDirection === 'asc' ? '↑' : '↓';
            const element = document.getElementById(`sort-${sortBy}`);
            if (element) {
                element.innerHTML = indicator;
            }
        }

        function searchBerkas() {
            searchTerm = document.getElementById('searchInput').value.trim();
            currentPage = 1; // Reset to first page when searching
            loadBerkasPeserta(currentPage);
        }

        function renderPagination(pagination) {
            const container = document.getElementById('pagination');
            container.innerHTML = '';

            // Left side - Page info
            const infoDiv = document.createElement('div');
            infoDiv.className = 'flex items-center gap-4';
            
            const info = document.createElement('span');
            info.className = 'text-sm text-gray-700';
            info.innerHTML = `Menampilkan halaman <span class="font-medium">${pagination.page}</span> dari <span class="font-medium">${pagination.total_pages}</span>`;
            infoDiv.appendChild(info);
            
            container.appendChild(infoDiv);

            // Right side - Navigation buttons
            const navDiv = document.createElement('div');
            navDiv.className = 'flex gap-2';

            // Previous button
            const prev = document.createElement('button');
            prev.innerHTML = 'Sebelumnya';
            prev.className = `px-3 py-1 border rounded-md ${pagination.page === 1 ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'}`;
            prev.disabled = pagination.page === 1;
            prev.onclick = () => pagination.page > 1 && loadBerkasPeserta(pagination.page - 1);
            navDiv.appendChild(prev);

            // Next button
            const next = document.createElement('button');
            next.innerHTML = 'Selanjutnya';
            next.className = `px-3 py-1 border rounded-md ${pagination.page === pagination.total_pages ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'}`;
            next.disabled = pagination.page === pagination.total_pages;
            next.onclick = () => pagination.page < pagination.total_pages && loadBerkasPeserta(pagination.page + 1);
            navDiv.appendChild(next);

            container.appendChild(navDiv);
            
            // Update current page variable
            currentPage = pagination.page;
        }

        function previewFile(url) {
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('filePreview').src = url;
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('filePreview').src = '';
        }

        async function deleteBerkas(id) {
            try {
                const response = await AwaitFetchApi(`admin/berkas/${id}`, 'DELETE');
                if (response.meta?.code === 200) {
                    showAlert('Berkas berhasil dihapus', 'success');
                    loadBerkasPeserta(currentPage); // Stay on current page after deletion
                } else {
                    showAlert(`Gagal menghapus berkas: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus berkas', 'error');
            }
        }
    </script>
@endsection

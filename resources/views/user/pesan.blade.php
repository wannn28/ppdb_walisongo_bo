@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Pesan</h1>
            <div class="flex gap-4">
                <button id="btnTrash" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center"
                    onclick="openTrashModal()">
                    <i class="fas fa-trash mr-2"></i> Trash
                </button>
                <button id="btnAddPesan"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center mr-2">
                    <i class="fas fa-plus mr-2"></i> Tambah Pesan
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="pesanTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
            
            <!-- Pagination Component -->
            @component('components.pagination', ['id' => 'pesanPagination', 'loadFunction' => 'loadPesan'])
            @endcomponent
        </div>
    </div>

    <!-- Modal Tambah/Edit Pesan -->
    <div id="pesanModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-1/2 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Pesan</h3>
                <form id="pesanForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">User ID</label>
                        <input type="number" id="user_id" name="user_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
                        <input type="text" id="judul" name="judul"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" rows="4"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" data-close-modal="pesanModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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

    <!-- Modal Detail Pesan -->
    <div id="detailPesanModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-2/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="detail-judul">Judul Pesan</h3>
                <button onclick="closeModal('detailPesanModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-4 pb-4 border-b border-gray-200">
                <div class="flex justify-between items-center text-sm text-gray-500 mb-2">
                    <div>
                        <span>Untuk: </span>
                        <span id="detail-user" class="font-medium">User Name</span>
                    </div>
                    <div>
                        <span id="detail-date" class="font-medium">01 Jan 2023</span>
                    </div>
                </div>
                <div class="mt-4">
                    <p id="detail-deskripsi" class="text-gray-700 whitespace-pre-line">
                        Deskripsi pesan akan ditampilkan di sini.
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button onclick="closeModal('detailPesanModal')"
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                    Tutup
                </button>
                <button id="btnEditFromDetail" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Edit
                </button>
                <button id="btnDeleteFromDetail" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Hapus
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal Trash Pesan -->
    <div id="trashPesanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Pesan Terhapus</h3>
                <button onclick="closeModal('trashPesanModal')" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-white rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dihapus Pada</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="trashPesanTableBody" class="bg-white divide-y divide-gray-200">
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
        let currentPesanId = null;
        let currentPage = 1;
        let totalPages = 1;
        let trashCurrentPage = 1;
        let trashTotalPages = 1;

        document.addEventListener('DOMContentLoaded', () => {
            loadPesan(1);

            // Event listeners for modals
            document.getElementById('btnAddPesan').addEventListener('click', () => {
                document.getElementById('pesanForm').reset();
                document.getElementById('pesanForm').removeAttribute('data-id');
                document.getElementById('modalTitle').textContent = 'Tambah Pesan';
                openModal('pesanModal');
            });

            // Edit from detail view
            document.getElementById('btnEditFromDetail').addEventListener('click', () => {
                closeModal('detailPesanModal');
                editPesan(currentPesanId);
            });

            // Delete from detail view
            document.getElementById('btnDeleteFromDetail').addEventListener('click', () => {
                closeModal('detailPesanModal');
                deletePesan(currentPesanId);
            });

            // Close modal buttons
            document.querySelectorAll('[data-close-modal]').forEach(button => {
                button.addEventListener('click', () => {
                    const modalId = button.getAttribute('data-close-modal');
                    closeModal(modalId);
                });
            });

            // Form submission
            document.getElementById('pesanForm').addEventListener('submit', handleFormSubmit);
            
            // Trash pagination event listeners
            document.getElementById('trash-prev-page').addEventListener('click', () => {
                if (trashCurrentPage > 1) {
                    loadTrashPesan(trashCurrentPage - 1);
                }
            });
            
            document.getElementById('trash-next-page').addEventListener('click', () => {
                if (trashCurrentPage < trashTotalPages) {
                    loadTrashPesan(trashCurrentPage + 1);
                }
            });
        });

        async function loadPesan(page = 1) {
            currentPage = page;
            try {
                const response = await AwaitFetchApi(`admin/pesan?page=${page}`, 'GET');
                print.log('API Response - Pesan:', response);

                const tableBody = document.getElementById('pesanTableBody');
                tableBody.innerHTML = '';

                if (!response.data || !response.data.data || response.data.data.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data pesan
                    </td>
                `;
                    tableBody.appendChild(emptyRow);
                    return;
                }

                // Update pagination info
                const paginationData = response.data;
                document.getElementById('pagination-start').textContent = paginationData.from;
                document.getElementById('pagination-end').textContent = paginationData.to;
                document.getElementById('pagination-total').textContent = paginationData.total;
                
                // Enable/disable pagination buttons
                document.getElementById('prev-page').disabled = currentPage === 1;
                document.getElementById('next-page').disabled = currentPage === paginationData.last_page;
                
                // Update page numbers
                updatePageNumbers(currentPage, paginationData.last_page);
                
                totalPages = paginationData.last_page;

                // Get pesan list from response
                const pesanList = paginationData.data;
                let startIndex = paginationData.from;

                pesanList.forEach((pesan, index) => {
                    const row = document.createElement('tr');
                    const statusClass = pesan.is_read ? 'bg-green-100 text-green-800' :
                        'bg-yellow-100 text-yellow-800';
                    const statusText = pesan.is_read ? 'Dibaca' : 'Belum dibaca';

                    row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${startIndex + index}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pesan.user ? pesan.user.name : `User ID: ${pesan.user_id}`}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${pesan.judul}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>${formatDate(pesan.created_at)}</div>
                        <div class="text-sm text-gray-500">${formatDate(pesan.created_at, true).split(' ')[1]}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewPesanDetail(${pesan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editPesan(${pesan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deletePesan(${pesan.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat data pesan', 'error');
            }
        }
        
        function updatePageNumbers(currentPage, lastPage) {
            const pageNumbersContainer = document.getElementById('page-numbers');
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
                    button.addEventListener('click', () => loadPesan(pageNum));
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

        async function viewPesanDetail(id) {
            try {
                const response = await AwaitFetchApi(`admin/pesan/${id}`, 'GET');
                if (response.meta?.code === 200) {
                    const pesan = response.data;

                    document.getElementById('detail-judul').textContent = pesan.judul;
                    document.getElementById('detail-user').textContent = pesan.user ? pesan.user.name :
                        `User ID: ${pesan.user_id}`;
                    document.getElementById('detail-date').textContent = formatDate(pesan.created_at, true);
                    document.getElementById('detail-deskripsi').textContent = pesan.deskripsi;

                    currentPesanId = pesan.id;
                    openModal('detailPesanModal');
                } else {
                    showNotification(response.meta?.message || 'Gagal memuat detail pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat detail pesan', 'error');
            }
        }

        async function editPesan(id) {
            try {
                const response = await AwaitFetchApi(`admin/pesan/${id}`, 'GET');
                if (response.meta?.code === 200) {
                    const pesan = response.data;

                    document.getElementById('user_id').value = pesan.user_id;
                    document.getElementById('judul').value = pesan.judul;
                    document.getElementById('deskripsi').value = pesan.deskripsi;

                    document.getElementById('pesanForm').setAttribute('data-id', id);
                    document.getElementById('modalTitle').textContent = 'Edit Pesan';
                    openModal('pesanModal');
                } else {
                    showNotification(response.meta?.message || 'Gagal memuat detail pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat detail pesan', 'error');
            }
        }

        async function deletePesan(id) {
            const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus ketentuan berkas ini?');

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await AwaitFetchApi(`admin/pesan/${id}`, 'DELETE');
                if (response.meta?.code === 200) {
                    showNotification(response.meta.message || 'Pesan berhasil dihapus', 'success');
                    loadPesan(currentPage);
                } else {
                    showNotification(response.meta?.message || 'Gagal menghapus pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat menghapus pesan', 'error');
            }
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const user_id = document.getElementById('user_id').value;
            const judul = document.getElementById('judul').value;
            const deskripsi = document.getElementById('deskripsi').value;

            if (!judul || !deskripsi) {
                showNotification('Judul dan deskripsi harus diisi', 'error');
                return;
            }

            try {
                let response;
                let data;

                if (id) {
                    data = {
                        judul: judul,
                        deskripsi: deskripsi
                    };
                    response = await AwaitFetchApi(`admin/pesan/${id}`, 'PUT', data);
                } else {
                    if (!user_id) {
                        showNotification('User ID harus diisi', 'error');
                        return;
                    }

                    data = {
                        user_id: parseInt(user_id),
                        judul: judul,
                        deskripsi: deskripsi
                    };
                    response = await AwaitFetchApi('admin/pesan', 'POST', data);
                }

                if (response.meta?.code === 200 || response.meta?.code === 201) {
                    showNotification(response.meta.message || 'Pesan berhasil disimpan', 'success');
                    closeModal('pesanModal');
                    loadPesan(currentPage);
                } else {
                    showNotification(response.meta?.message || 'Gagal menyimpan pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyimpan pesan', 'error');
            }
        }

        async function loadTrashPesan(page = 1) {
            trashCurrentPage = page;
            try {
                const params = new URLSearchParams({
                    page: page,
                    per_page: 10
                });

                const response = await AwaitFetchApi(`admin/pesans/trash?${params}`, 'GET');
                print.log('API Response - Pesan Trash:', response);
                
                const tableBody = document.getElementById('trashPesanTableBody');
                tableBody.innerHTML = '';
                
                if (!response.data || response.data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada pesan terhapus
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                // Update pagination info
                const paginationData = response.data;
                document.getElementById('trash-pagination-start').textContent = paginationData.from || 0;
                document.getElementById('trash-pagination-end').textContent = paginationData.to || 0;
                document.getElementById('trash-pagination-total').textContent = paginationData.total || 0;
                
                // Enable/disable pagination buttons
                document.getElementById('trash-prev-page').disabled = trashCurrentPage === 1;
                document.getElementById('trash-next-page').disabled = trashCurrentPage === (paginationData.last_page || 1);
                
                // Update page numbers
                updateTrashPageNumbers(trashCurrentPage, paginationData.last_page || 1);
                
                trashTotalPages = paginationData.last_page || 1;
                
                // Get pesan list from response
                const pesanList = paginationData.data || [];
                
                let startIndex = paginationData.from || 1;
                
                pesanList.forEach((pesan, index) => {
                    const row = document.createElement('tr');
                    const statusClass = pesan.is_read ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800';
                    const statusText = pesan.is_read ? 'Dibaca' : 'Belum dibaca';
                    
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${startIndex + index}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${pesan.user ? pesan.user.name : `User ID: ${pesan.user_id}`}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${pesan.judul}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded ${statusClass}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>${formatDate(pesan.created_at)}</div>
                            <div class="text-sm text-gray-500">${formatDate(pesan.created_at, true).split(' ')[1]}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>${formatDate(pesan.deleted_at)}</div>
                            <div class="text-sm text-gray-500">${formatDate(pesan.deleted_at, true).split(' ')[1]}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="restorePesan(${pesan.id})" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-trash-restore"></i> Restore
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat data pesan terhapus', 'error');
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
                    button.addEventListener('click', () => loadTrashPesan(pageNum));
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
        
        async function restorePesan(id) {
            const result = await showDeleteConfirmation(
                'Apakah Anda yakin ingin memulihkan pesan ini?',
                'Ya, Pulihkan',
                'Batal'
            );
            
            if (!result.isConfirmed) {
                return;
            }
            
            try {
                const response = await AwaitFetchApi(`admin/pesan/${id}/restore`, 'PUT');
                
                if (response.meta?.code === 200) {
                    showNotification(response.meta.message || 'Pesan berhasil dipulihkan', 'success');
                    loadTrashPesan(trashCurrentPage);
                    loadPesan(currentPage);
                } else {
                    showNotification(response.meta?.message || 'Gagal memulihkan pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memulihkan pesan', 'error');
            }
        }
        
        function openTrashModal() {
            loadTrashPesan();
            openModal('trashPesanModal');
        }
        
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }
        
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
@endpush

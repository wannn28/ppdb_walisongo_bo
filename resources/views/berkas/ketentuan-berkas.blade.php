@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Ketentuan Berkas</h1>
            <div class="flex gap-4">
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center"
                    onclick="openTrashModal()">
                    <i class="fas fa-trash mr-2"></i> Trash
                </button>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center"
                    onclick="openModal('berkasModal')">
                    <i class="fas fa-plus mr-2"></i> Tambah Ketentuan Berkas
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama
                            Berkas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang
                            Sekolah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Data akan diisi oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit Ketentuan Berkas -->
    <div id="berkasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Tambah Ketentuan Berkas</h3>
                <form id="berkasForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="namaBerkas">
                            Nama Berkas
                        </label>
                        <input type="text" id="namaBerkas" name="namaBerkas"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Jenjang Sekolah
                        </label>
                        <div class="flex gap-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jenjang[]" value="SD" class="form-checkbox">
                                <span class="ml-2">SD</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jenjang[]" value="SMP" class="form-checkbox">
                                <span class="ml-2">SMP</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jenjang[]" value="SMA" class="form-checkbox">
                                <span class="ml-2">SMA</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jenjang[]" value="SMK" class="form-checkbox">
                                <span class="ml-2">SMK</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Required
                        </label>
                        <div class="flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="required" value="1" class="form-radio" checked>
                                <span class="ml-2">Ya</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="required" value="0" class="form-radio">
                                <span class="ml-2">Tidak</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" data-close-modal="berkasModal"
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

    <!-- Modal Trash Ketentuan Berkas -->
    <div id="trashModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Ketentuan Berkas Terhapus</h3>
                <button data-close-modal="trashModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="bg-white rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Berkas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang Sekolah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="trashTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data akan diisi oleh JavaScript -->
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

    <script>
        // Global variables for pagination
        let trashCurrentPage = 1;
        let trashTotalPages = 1;

        document.addEventListener('DOMContentLoaded', () => {
            loadKetentuanBerkas();
            
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
        
        // Fungsi untuk memuat semua ketentuan berkas
        async function loadKetentuanBerkas() {
            try {
                const response = await AwaitFetchApi('admin/ketentuan-berkas', 'GET');
                if (response.meta?.code === 200) {
                    // Perbaikan path data yang benar
                    renderKetentuanBerkas(response.data || {});
                } else {
                    showNotification('Gagal memuat ketentuan berkas: ' + response.meta?.message, 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat ketentuan berkas', 'error');
            }
        }

        function renderKetentuanBerkas(data) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            // Pastikan mengambil dari property yang benar
            const ketentuanBerkas = data.ketentuan_berkas || [];

            if (!ketentuanBerkas.length) {
                tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                    Belum ada data ketentuan berkas
                </td>
            </tr>
        `;
                return;
            }

            ketentuanBerkas.forEach(item => {
                // Handle kemungkinan penulisan jenjang salah
                const jenjang = item.jenjang_sekolah.toUpperCase().trim();
                const jenjangArray = jenjang.split(',').filter(j => ['SD', 'SMP', 'SMA', 'SMK'].includes(j));

                const row = document.createElement('tr');
                row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">${item.id}</td>
            <td class="px-6 py-4 whitespace-nowrap">${item.nama}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${jenjangArray.map(jenjang => `
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 mr-1">${jenjang}</span>
                        `).join('')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.is_required ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${item.is_required ? 'Ya' : 'Tidak'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="editBerkas(${item.id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="text-red-600 hover:text-red-900" onclick="deleteBerkas(${item.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
                tbody.appendChild(row);
            });
        }

        // Fungsi untuk memuat data di trash
        async function loadTrashData(page = 1) {
            try {
                const params = new URLSearchParams({
                    page: page,
                    per_page: 10
                });

                const response = await AwaitFetchApi(`admin/ketentuan-berkass/trash?${params}`, 'GET');
                print.log('API Response - Ketentuan Berkas Trash:', response);
                
                const tableBody = document.getElementById('trashTableBody');
                tableBody.innerHTML = '';
                
                if (!response.data || !response.data.ketentuan_berkas || response.data.ketentuan_berkas.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Tidak ada data ketentuan berkas terhapus
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                // Render trash data
                const ketentuanBerkas = response.data.ketentuan_berkas || [];
                
                ketentuanBerkas.forEach(item => {
                    const jenjang = item.jenjang_sekolah.toUpperCase().trim();
                    const jenjangArray = jenjang.split(',').filter(j => ['SD', 'SMP', 'SMA', 'SMK'].includes(j));
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${item.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${item.nama}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${jenjangArray.map(jenjang => `
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 mr-1">${jenjang}</span>
                            `).join('')}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${item.is_required ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${item.is_required ? 'Ya' : 'Tidak'}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${item.deleted_at ? new Date(item.deleted_at).toLocaleString() : '-'}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-green-600 hover:text-green-900" onclick="restoreBerkas(${item.id})">
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
                showNotification('Terjadi kesalahan saat memuat data ketentuan berkas terhapus', 'error');
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

        // Perbaikan 3: Update fungsi editBerkas
        async function editBerkas(id) {
            try {
                const response = await AwaitFetchApi(`admin/ketentuan-berkas/${id}`, 'GET');
                if (response.meta?.code === 200) {
                    const data = response.data;
                    document.getElementById('namaBerkas').value = data.nama;
                    
                    // Reset all checkboxes first
                    document.querySelectorAll('input[name="jenjang[]"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    
                    // Check if we have multiple records with the same ID but different jenjang
                    if (Array.isArray(data)) {
                        // If we got an array of records with the same ID
                        const uniqueJenjang = [...new Set(data.map(item => item.jenjang_sekolah))];
                        
                        document.querySelectorAll('input[name="jenjang[]"]').forEach(checkbox => {
                            checkbox.checked = uniqueJenjang.includes(checkbox.value);
                        });
                        
                        // Use the required value from the first item (should be the same for all)
                        if (data.length > 0) {
                            document.querySelector(`input[name="required"][value="${data[0].is_required ? 1 : 0}"]`).checked = true;
                        }
                    } else {
                        // Handle single record (for backward compatibility)
                        const jenjangArr = data.jenjang_sekolah.split(',');
                        document.querySelectorAll('input[name="jenjang[]"]').forEach(checkbox => {
                            checkbox.checked = jenjangArr.includes(checkbox.value);
                        });
                        document.querySelector(`input[name="required"][value="${data.is_required ? 1 : 0}"]`).checked = true;
                    }
                    
                    document.getElementById('berkasForm').setAttribute('data-id', id);
                    document.getElementById('modalTitle').textContent = 'Edit Ketentuan Berkas';
                    openModal('berkasModal');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat mengambil data berkas', 'error');
            }
        }

        // Fungsi untuk hapus berkas
        async function deleteBerkas(id) {
            const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus ketentuan berkas ini?');

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await AwaitFetchApi(`admin/ketentuan-berkas/${id}`, 'DELETE');
                if (response.meta?.code === 200) {
                    showNotification(response.meta.message || 'Ketentuan berkas berhasil dihapus', 'success');
                    loadKetentuanBerkas();
                } else {
                    showNotification(response.meta?.message || 'Gagal menghapus ketentuan berkas', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat menghapus ketentuan berkas', 'error');
            }
        }
        
        // Fungsi untuk restore berkas
        async function restoreBerkas(id) {
            const result = await showDeleteConfirmation(
                'Apakah Anda yakin ingin memulihkan ketentuan berkas ini?',
                'Ya, Pulihkan',
                'Batal'
            );
            
            if (!result.isConfirmed) {
                return;
            }
            
            try {
                const response = await AwaitFetchApi(`admin/ketentuan-berkas/${id}/restore`, 'PUT');
                
                if (response.meta?.code === 200) {
                    showNotification(response.meta.message || 'Ketentuan berkas berhasil dipulihkan', 'success');
                    loadTrashData(trashCurrentPage);
                    loadKetentuanBerkas();
                } else {
                    showNotification(response.meta?.message || 'Gagal memulihkan ketentuan berkas', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memulihkan ketentuan berkas', 'error');
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
        
        // Handle close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });

        // Handle form submission
        document.getElementById('berkasForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = this.getAttribute('data-id');
            const namaBerkas = document.getElementById('namaBerkas').value;
            const jenjangCheckboxes = Array.from(document.querySelectorAll('input[name="jenjang[]"]:checked'))
                .map(cb => cb.value);
            const isRequired = document.querySelector('input[name="required"]:checked').value;
            
            if (!namaBerkas) {
                showNotification('Nama berkas tidak boleh kosong', 'error');
                return;
            }
            
            if (jenjangCheckboxes.length === 0) {
                showNotification('Pilih minimal satu jenjang sekolah', 'error');
                return;
            }

            try {
                // Send separate API requests for each selected jenjang
                const promises = jenjangCheckboxes.map(jenjang => {
                    const data = {
                        nama: namaBerkas,
                        jenjang_sekolah: jenjang,
                        is_required: isRequired
                    };
                    
                    if (id) {
                        return AwaitFetchApi(`admin/ketentuan-berkas/${id}`, 'PUT', data);
                    } else {
                        return AwaitFetchApi('admin/ketentuan-berkas', 'POST', data);
                    }
                });
                
                const responses = await Promise.all(promises);
                
                // Check if all requests were successful
                const allSuccessful = responses.every(response => 
                    response.meta?.code === 200 || response.meta?.code === 201
                );
                
                if (allSuccessful) {
                    showNotification('Semua ketentuan berkas berhasil disimpan', 'success');
                    closeModal('berkasModal');
                    loadKetentuanBerkas();
                } else {
                    showNotification('Beberapa ketentuan berkas gagal disimpan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat menyimpan ketentuan berkas', 'error');
            }
        });
    </script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen Kelas</h1>
        <div class="flex gap-4">
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="openTrashModal()">
                <i class="fas fa-trash mr-2"></i> Trash
            </button>
            <button id="btnAddJurusan" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Kelas
            </button>
        </div>
    </div>
    
    <!-- Filter Controls -->
    <x-filter resetFunction="resetFilters">
        <x-filter-text 
            id="searchInput" 
            label="Cari Kelas" 
            placeholder="Cari nama kelas..." 
            onChangeFunction="updateSearchFilter" />
        
        <x-filter-select 
            id="jenjangFilter" 
            label="Jenjang Sekolah" 
            :options="[''=>'Semua Jenjang', 'SMP'=>'SMP', 'SMA'=>'SMA', 'SMK'=>'SMK']" 
            onChangeFunction="updateJenjangFilter" />
    </x-filter>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang Sekolah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody id="jurusanTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
        
        <!-- Add pagination component -->
        <x-pagination loadFunction="loadJurusan" />
    </div>
</div>

<!-- Modal Tambah/Edit Kelas -->
<div id="jurusanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 id="modalTitle" class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Kelas</h3>
            <form id="jurusanForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kelas</label>
                    <input type="text" id="jurusan" name="jurusan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jenjang Sekolah</label>
                    <select id="jenjang_sekolah" name="jenjang_sekolah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Pilih Jenjang Sekolah</option>
                        {{-- <option value="SD">SD</option> --}}
                        {{-- <option value="SMP 1">SMP 1</option> --}}
                        {{-- <option value="SMP 2">SMP 2</option> --}}
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA</option>
                        <option value="SMK">SMK</option>
                    </select>
                </div>
                
                <div class="flex justify-end gap-2">
                    <button type="button" data-close-modal="jurusanModal" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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

<!-- Modal Trash Kelas -->
<div id="trashModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
    <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Kelas Terhapus</h3>
            <button data-close-modal="trashModal" class="text-gray-400 hover:text-gray-500">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="bg-white rounded-lg overflow-x-auto w-full">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenjang Sekolah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="trashTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global variables for filters and pagination
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 10;
    let sortBy = '';
    let sortDirection = 'asc';
    let jenjang_sekolah = localStorage.getItem('jenjang_sekolah');
    let filters = {
        search: '',
        jenjang_sekolah: jenjang_sekolah
    };

    // Filter functions
    function updateSearchFilter(value) {
        filters.search = value;
        currentPage = 1; // Reset to first page when filtering
        loadJurusan();
    }
    
    function updateJenjangFilter(value) {
        document.getElementById('jenjangFilter').value = value;
        filters.jenjang_sekolah = value;
        currentPage = 1;
        loadJurusan();
    }
    
    function resetFilters() {
        filters = {
            search: '',
            jenjang_sekolah: jenjang_sekolah
        };
        
        // Reset form inputs
        document.getElementById('searchInput').value = '';
        document.getElementById('jenjangFilter').value = jenjang_sekolah || '';
        
        currentPage = 1;
        loadJurusan();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateJenjangFilter(jenjang_sekolah || '');
        loadJurusan();
        
        // Event listeners for modals
        document.getElementById('btnAddJurusan').addEventListener('click', () => {
            document.getElementById('jurusanForm').reset();
            document.getElementById('jurusanForm').removeAttribute('data-id');
            document.getElementById('modalTitle').textContent = 'Tambah Kelas';
            openModal('jurusanModal');
        });
        
        // Close modal buttons
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', () => {
                const modalId = button.getAttribute('data-close-modal');
                closeModal(modalId);
            });
        });
        
        // Form submission
        document.getElementById('jurusanForm').addEventListener('submit', handleFormSubmit);
    });

    async function loadJurusan(page = 1) {
        try {
            currentPage = page;
            const params = new URLSearchParams({
                page: page,
                per_page: perPage
            });
            
            // Add filters to params if they have values
            if (filters.search) params.append('search', filters.search);
            if (filters.jenjang_sekolah) params.append('jenjang_sekolah', filters.jenjang_sekolah);
            
            if (sortBy) {
                params.append('sort_by', sortBy);
                params.append('sort_direction', sortDirection);
            }
            
            const response = await AwaitFetchApi(`admin/jurusan?${params.toString()}`, 'GET');
            print.log('API Response - Kelas:', response);
            
            const tableBody = document.getElementById('jurusanTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || !response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data kelas
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Use the jurusan array from the response
            const jurusanList = response.data || [];
            
            jurusanList.forEach((jurusan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${jurusan.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${capitalizeWords(jurusan.jurusan)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                            ${jurusan.jenjang_sekolah}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="editJurusan(${jurusan.id})" class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteJurusan(${jurusan.id})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Update pagination if present in response
            if (response.pagination) {
                updatePagination(response.pagination);
            }
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data kelas', 'error');
        }
    }
    
    // Function to update pagination based on the pagination data from API
    function updatePagination(pagination) {
        if (!pagination) return;
        
        // Default values in case pagination data is missing or malformed
        let currentPageValue = parseInt(pagination.page) || 1;
        let totalPagesValue = parseInt(pagination.total_pages) || 1;
        let totalItemsValue = parseInt(pagination.total_items) || 0;
        let perPageValue = parseInt(pagination.per_page) || 10;
        
        // Update global variables
        currentPage = currentPageValue;
        totalPages = totalPagesValue;
        perPage = perPageValue;
        
        // Calculate start and end values
        const start = totalItemsValue > 0 ? (currentPageValue - 1) * perPageValue + 1 : 0;
        const end = Math.min(start + perPageValue - 1, totalItemsValue);
        
        // Update DOM elements
        document.getElementById('pagination-start').textContent = start;
        document.getElementById('pagination-end').textContent = end;
        document.getElementById('pagination-total').textContent = totalItemsValue;
        
        // Update previous and next buttons state
        document.getElementById('prev-page').disabled = currentPageValue <= 1;
        document.getElementById('next-page').disabled = currentPageValue >= totalPagesValue;
        
        // Generate page numbers
        const pageNumbers = document.getElementById('page-numbers');
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
            firstPageBtn.onclick = () => loadJurusan(1);
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
            pageBtn.className =
                `px-3 py-1 rounded border ${currentPageValue === i ? 'bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}`;
            pageBtn.textContent = i;
            pageBtn.onclick = () => loadJurusan(i);
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
            lastPageBtn.onclick = () => loadJurusan(totalPagesValue);
            pageNumbers.appendChild(lastPageBtn);
        }
    }
    
    async function loadTrashData() {
        try {
            const response = await AwaitFetchApi('admin/jurusans/trash', 'GET');
            print.log('API Response - Kelas Trash:', response);
            
            const tableBody = document.getElementById('trashTableBody');
            tableBody.innerHTML = '';
            
            if (!response.data || response.data.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data kelas terhapus
                    </td>
                `;
                tableBody.appendChild(emptyRow);
                return;
            }
            
            // Check if data is in response.data or response.data.data based on API structure
            const jurusanList = Array.isArray(response.data) ? response.data : (response.data.data || []);
            
            jurusanList.forEach((jurusan, index) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${capitalizeWords(jurusan.jurusan)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                            ${jurusan.jenjang_sekolah}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${jurusan.deleted_at ? new Date(jurusan.deleted_at).toLocaleString() : '-'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="restoreJurusan(${jurusan.id})" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-trash-restore"></i> Restore
                        </button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat data kelas terhapus', 'error');
        }
    }
    
    async function editJurusan(id) {
        try {
            const response = await AwaitFetchApi(`admin/jurusan/${id}`, 'GET');
            if (response.meta?.code === 200) {
                const jurusan = response.data;
                document.getElementById('jurusan').value = jurusan.jurusan;
                document.getElementById('jenjang_sekolah').value = jurusan.jenjang_sekolah;
                document.getElementById('jurusanForm').setAttribute('data-id', id);
                document.getElementById('modalTitle').textContent = 'Edit Kelas';
                openModal('jurusanModal');
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memuat detail kelas', 'error');
        }
    }
    
    async function deleteJurusan(id) {
        const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus kelas ini?');

        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/jurusan/${id}`, 'DELETE');
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Kelas berhasil dihapus', 'success');
                loadJurusan();
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus kelas', 'error');
        }
    }
    
    async function restoreJurusan(id) {
        const result = await showDeleteConfirmation('Apakah Anda yakin ingin memulihkan kelas ini?', 'Ya, Pulihkan', 'Batal');

        if (!result.isConfirmed) {
            return;
        }
        
        try {
            const response = await AwaitFetchApi(`admin/jurusan/${id}/restore`, 'PUT');
            if (response.meta?.code === 200) {
                showNotification(response.meta.message || 'Kelas berhasil dipulihkan', 'success');
                loadTrashData();
                loadJurusan();
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat memulihkan kelas', 'error');
        }
    }
    
    function openTrashModal() {
        loadTrashData();
        openModal('trashModal');
    }
    
    async function handleFormSubmit(e) {
        e.preventDefault();
        
        const id = this.getAttribute('data-id');
        const jurusan = document.getElementById('jurusan').value.toLowerCase();
        const jenjang_sekolah = document.getElementById('jenjang_sekolah').value;
        
        if (!jurusan || !jenjang_sekolah) {
            showNotification('Semua field harus diisi', 'error');
            return;
        }
        
        const data = {
            jurusan: jurusan,
            jenjang_sekolah: jenjang_sekolah
        };
        
        try {
            let response;
            
            if (id) {
                response = await AwaitFetchApi(`admin/jurusan/${id}`, 'PUT', data);
            } else {
                response = await AwaitFetchApi('admin/jurusan', 'POST', data);
            }
            
            if (response.meta?.code === 200 || response.meta?.code === 201) {
                showNotification(response.meta.message || 'Kelas berhasil disimpan', 'success');
                closeModal('jurusanModal');
                loadJurusan();
            } 
        } catch (error) {
            print.error('Error:', error);
            showNotification('Terjadi kesalahan saat menyimpan kelas', 'error');
        }
    }
    
    // Helper function to capitalize each word
    function capitalizeWords(str) {
        return str.replace(/\b\w/g, char => char.toUpperCase());
    }
    
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }
    
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endpush 
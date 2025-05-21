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

        <!-- Filter Controls -->
        <x-filter resetFunction="resetFilters">
            <x-filter-text 
                id="searchInput" 
                label="Cari Berkas" 
                placeholder="Cari nama berkas..." 
                onChangeFunction="updateSearchFilter" />
            
            <x-filter-select 
                id="jenjangFilter" 
                label="Jenjang Sekolah" 
                :options="[''=>'Semua Jenjang', 'SD'=>'SD', 'SMP 1'=>'SMP 1', 'SMP 2'=>'SMP 2', 'SMA'=>'SMA', 'SMK'=>'SMK']" 
                onChangeFunction="updateJenjangFilter" />
            
            <x-filter-select 
                id="requiredFilter" 
                label="Required" 
                :options="[''=>'Semua', '1'=>'Ya', '0'=>'Tidak']" 
                onChangeFunction="updateRequiredFilter" />
        </x-filter>

        <div class="bg-white rounded-lg shadow-md mt-6">
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
        
        <!-- Tambahkan pagination component untuk tabel utama -->
        @component('components.pagination', ['id' => 'main-pagination', 'loadFunction' => 'loadKetentuanBerkas'])
        @endcomponent
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
                                <input type="checkbox" name="jenjang[]" value="SMP 1" class="form-checkbox">
                                <span class="ml-2">SMP 1</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="jenjang[]" value="SMP 2" class="form-checkbox">
                                <span class="ml-2">SMP 2</span>
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
                @component('components.pagination', ['id' => 'trash-pagination', 'loadFunction' => 'loadTrashData'])
                @endcomponent
            </div>
        </div>
    </div>

    @component('components.table-utils')
    @endcomponent

    <script>
        // Global variables for pagination
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10;
        let sortBy = '';
        let sortDirection = 'asc';
        let jenjang_sekolah = localStorage.getItem('jenjang_sekolah');
        let filters = {
            search: '',
            jenjang_sekolah: jenjang_sekolah,
            is_required: ''
        };
        // Filter functions
        function updateSearchFilter(value) {
            filters.search = value;
            currentPage = 1; // Reset to first page when filtering
            loadKetentuanBerkas();
        }
        
        function updateJenjangFilter(value) {
            filters.jenjang_sekolah = value;
            currentPage = 1;
            loadKetentuanBerkas();
        }
        
        function updateRequiredFilter(value) {
            // Convert to numeric value for is_required if needed
            filters.is_required = value;
            currentPage = 1;
            loadKetentuanBerkas();
        }
        
        function resetFilters() {
            filters = {
                search: '',
                jenjang_sekolah: jenjang_sekolah,
                is_required: ''
            };
            
            // Reset form inputs
            document.getElementById('searchInput').value = '';
            document.getElementById('jenjangFilter').value = '';
            document.getElementById('requiredFilter').value = '';
            
            currentPage = 1;
            loadKetentuanBerkas();
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            loadKetentuanBerkas();
        });
        
        // Fungsi untuk memuat semua ketentuan berkas
        async function loadKetentuanBerkas(page = 1) {
            try {
                currentPage = page;
                const params = new URLSearchParams({
                    page: page,
                    per_page: perPage,
                    sort_by: sortBy,
                    sort_direction: sortDirection
                });
                
                // Add filters to params if they have values
                if (filters.search) params.append('search', filters.search);
                if (filters.jenjang_sekolah) params.append('jenjang_sekolah', filters.jenjang_sekolah);
                if (filters.is_required !== undefined && filters.is_required !== '') {
                    params.append('is_required', filters.is_required);
                }
                
                // Debug: Log the params being sent
                print.log('Filter params:', { 
                    search: filters.search,
                    jenjang_sekolah: filters.jenjang_sekolah,
                    is_required: filters.is_required 
                });
                
                const requestUrl = `admin/ketentuan-berkas?${params}`;
                print.log('Request URL:', requestUrl);
                
                const response = await AwaitFetchApi(requestUrl, 'GET');
                
                print.log('API Response:', response);
                
                if (response.meta?.code === 200) {
                    // Handle the correct data structure with nested ketentuan_berkas
                    renderKetentuanBerkas(response.data || {});
                    // Update pagination for main table using the utility function
                    updatePaginationElements(response.pagination, loadKetentuanBerkas);
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
                const jenjangArray = jenjang.split(',').filter(j => ['SD', 'SMP 1', 'SMP 2', 'SMA', 'SMK'].includes(j));

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
                    per_page: perPage
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
                    const jenjangArray = jenjang.split(',').filter(j => ['SD', 'SMP 1', 'SMP 2', 'SMA', 'SMK'].includes(j));
                    
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
                
                // Update pagination using utility function
                updatePaginationElements(response.pagination, loadTrashData);
            } catch (error) {
                print.error('Error:', error);
                showNotification('Terjadi kesalahan saat memuat data ketentuan berkas terhapus', 'error');
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
                    loadTrashData(currentPage);
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

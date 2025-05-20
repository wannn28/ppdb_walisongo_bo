@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-4 md:mb-0">Detail Peserta PPDB</h1>
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <x-search placeholder="Cari Peserta..." searchFunction="searchPeserta"
                    additionalClasses="bg-transparent shadow-none p-0 w-full sm:w-auto" />
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center"
                    onclick="openModal('trashModal')">
                    <i class="fas fa-trash mr-2"></i> Trash
                </button>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <x-sortable-header column="id" label="ID"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="nisn" label="NISN"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="nis" label="NIS"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="nama" label="Nama"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="tempat_lahir" label="TTL"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="jenis_kelamin" label="Gender"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="no_telp" label="Kontak"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="jenjang_sekolah" label="Jenjang"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="angkatan" label="Angkatan"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="status" label="Status"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="penghasilan_ortu" label="Penghasilan Ortu"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <th
                            class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="pesertaTableBody">
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>

            <!-- Pagination Component -->
            <x-pagination loadFunction="loadPesertaData" />
        </div>
    </div>

    <!-- Modal Tambah Peserta -->
    <div id="tambahModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Peserta Baru</h3>
                <form id="pesertaForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                        <input type="text" name="nama"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">NISN</label>
                            <input type="text" name="nisn"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" data-close-modal="tambahModal"
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
    <!-- Modal Detail Peserta -->
    <div id="detailModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Detail Peserta PPDB</h3>
                <button data-close-modal="detailModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Data Pribadi</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NISN</label>
                            <p class="mt-1" id="detail-nisn">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <p class="mt-1" id="detail-nama">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tempat, Tanggal Lahir</label>
                            <p class="mt-1" id="detail-ttl">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                            <p class="mt-1" id="detail-gender">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <p class="mt-1" id="detail-alamat">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <p class="mt-1" id="detail-telp">-</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1 flex items-center gap-2">
                                <select id="detail-status"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="proses">Proses</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                                <button onclick="updatePesertaStatus()"
                                    class="px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIS</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="text" id="detail-nis-input"
                                    class="shadow appearance-none border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    placeholder="Masukkan NIS">
                                <button onclick="updateNisPeserta()"
                                    class="px-3 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                                    <i class="fas fa-save"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Informasi Pendidikan</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenjang Sekolah</label>
                            <p class="mt-1" id="detail-jenjang">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pilihan Kelas</label>
                            <p class="mt-1" id="detail-jurusan1">-</p>
                        </div>
                        <div id="detail-user-container" class="border-t border-gray-200 mt-4 pt-4">
                            <label class="block text-sm font-medium text-gray-700">ID User</label>
                            <p class="mt-1" id="detail-user-id">-</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Data Orang Tua</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                            <p class="mt-1" id="detail-nama-ayah">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pekerjaan Ayah</label>
                            <p class="mt-1" id="detail-pekerjaan-ayah">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                            <p class="mt-1" id="detail-nama-ibu">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Pekerjaan Ibu</label>
                            <p class="mt-1" id="detail-pekerjaan-ibu">-</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penghasilan Orang Tua</label>
                            <p class="mt-1" id="detail-penghasilan-ortu">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Informasi Tambahan</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm text-gray-500">
                    <div>
                        <span>Terdaftar pada:</span>
                        <p id="detail-created" class="font-medium">-</p>
                    </div>
                    <div>
                        <span>Terakhir diupdate:</span>
                        <p id="detail-updated" class="font-medium">-</p>
                    </div>
                    <div>
                        <span>ID Peserta:</span>
                        <p id="detail-id" class="font-medium">-</p>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Berkas Peserta</h4>
                <div id="berkas-container" class="grid grid-cols-1 gap-4">
                    <p id="berkas-loading" class="text-gray-500">Memuat berkas...</p>
                    <div id="berkas-list" class="hidden space-y-2"></div>
                    <p id="berkas-empty" class="hidden text-gray-500">Belum ada berkas yang diunggah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Trash Peserta -->
    <div id="trashModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Peserta Terhapus</h3>
                <button data-close-modal="trashModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="bg-white rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NISN
                            </th>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenjang</th>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Deleted At</th>
                            <th
                                class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="trashTableBody">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>

                <!-- Pagination for trash -->
                <div
                    class="bg-white px-4 py-3 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex justify-between w-full sm:hidden mb-4">
                        <button id="trash-prev-page"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </button>
                        <button id="trash-next-page"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </button>
                    </div>
                    <div class="flex-1 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div class="mb-4 sm:mb-0">
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
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                aria-label="Pagination">
                                <button id="trash-prev-page"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <div id="trash-page-numbers" class="flex"></div>
                                <button id="trash-next-page"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

    <x-table-utils />

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let sortBy = 'id';
        let sortDirection = 'asc';
        let searchTerm = '';
        let perPage = 10;
        let currentPesertaId = null;
        let allPeserta = [];
        let trashCurrentPage = 1;
        let trashTotalPages = 1;

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

            // Setup search input listener
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchPeserta();
                }
            });

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

        async function loadPesertaData(page = 1) {
            try {
                let sortField = sortBy;
                let clientSideSorting = false;

                // For nested relations like penghasilan_ortu, use client-side sorting
                if (sortBy === 'penghasilan_ortu') {
                    // Use a standard field for the API call since we'll sort client-side
                    sortField = 'id';
                    clientSideSorting = true;
                }

                const url =
                    `admin/pesertas?page=${page}&per_page=${perPage}&sort_by=${sortField}&order_by=${sortDirection}${searchTerm ? '&search=' + searchTerm : ''}`;
                const response = await AwaitFetchApi(url, 'GET');

                if (response?.data) {
                    let dataToRender = response.data;

                    // Perform client-side sorting for penghasilan_ortu if needed
                    if (clientSideSorting && sortBy === 'penghasilan_ortu') {
                        dataToRender = sortPesertaByPenghasilanOrtu(dataToRender, sortDirection);
                    }

                    renderPesertaTable(dataToRender);
                    updatePagination(response.pagination);
                    updateSortIndicators();
                }
            } catch (error) {
                print.error('Error loading peserta data:', error);
                showNotification('Gagal memuat data peserta', 'error');
            }
        }

        function renderPesertaTable(pesertas) {
            const tbody = document.getElementById('pesertaTableBody');
            tbody.innerHTML = '';

            if (pesertas.length === 0) {
                const emptyRow = `
            <tr>
                <td colspan="12" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data peserta yang ditemukan
                </td>
            </tr>
        `;
                tbody.innerHTML = emptyRow;
                return;
            }

            pesertas.forEach((peserta) => {
                const statusBadge = getStatusBadge(peserta.status);

                const row = `
            <tr>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.id}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.nisn || '-'}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.nis || '-'}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.nama}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <div>${peserta.tempat_lahir || '-'}</div>
                    <div class="text-xs text-gray-500">${peserta.tanggal_lahir ? new Date(peserta.tanggal_lahir).toLocaleDateString() : '-'}</div>
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                        ${peserta.jenis_kelamin || '-'}
                    </span>
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <div>${peserta.no_telp || '-'}</div>
                    <div class="text-xs text-gray-500">${peserta.biodata_ortu ? 'Data lengkap' : 'Belum lengkap'}</div>
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                        ${peserta.jenjang_sekolah || '-'}
                    </span>
                    ${peserta.jurusan1 ? `<div class="text-xs mt-1">Kelas : ${peserta.jurusan1.jurusan || '-'}</div>` : ''}
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">
                        ${peserta.angkatan || '-'}
                    </span>
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    ${statusBadge}
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    ${getPenghasilanOrtu(peserta)}
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button class="text-blue-600 hover:text-blue-900" onclick="viewDetail(${peserta.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" onclick="deletePeserta(${peserta.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
                tbody.innerHTML += row;
            });
        }

        function getStatusBadge(status) {
            if (!status) return '<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">Pending</span>';

            switch (status.toLowerCase()) {
                case 'Diterima':
                    return '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Diterima</span>';
                case 'diterima':
                    return '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">Diterima</span>';
                case 'Ditolak':
                    return '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>';
                case 'ditolak':
                    return '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">Ditolak</span>';
                case 'diproses':
                    return '<span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Proses</span>';
                case 'Diproses':
                    return '<span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800">Proses</span>';
                default:
                    return '<span class="px-2 py-1 text-xs rounded">-</span>';
            }
        }

        function getPenghasilanOrtu(peserta) {
            // If penghasilan_ortu is directly available on peserta object
            if (peserta.penghasilan_ortu) {
                if (typeof peserta.penghasilan_ortu === 'object') {
                    return `<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                ${peserta.penghasilan_ortu.penghasilan || '-'}
            </span>`;
                } else {
                    return `<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                ${peserta.penghasilan_ortu}
            </span>`;
                }
            }

            // If penghasilan_ortu is inside biodata_ortu object
            if (peserta.biodata_ortu && peserta.biodata_ortu.penghasilan_ortu) {
                if (typeof peserta.biodata_ortu.penghasilan_ortu === 'object') {
                    return `<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                ${peserta.biodata_ortu.penghasilan_ortu.penghasilan || '-'}
            </span>`;
                } else {
                    return `<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                ${peserta.biodata_ortu.penghasilan_ortu}
            </span>`;
                }
            }

            // Default value if not found
            return '<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800">-</span>';
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
            loadPesertaData(currentPage);
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

        function searchPeserta() {
            searchTerm = document.getElementById('searchInput').value.trim();
            currentPage = 1; // Reset to first page when searching
            loadPesertaData(currentPage);
        }

        function updatePagination(pagination) {
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
                currentPage = currentPageValue;
                totalPages = totalPagesValue;
                perPage = perPageValue;
            }

            // Calculate start and end values, protecting against NaN
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
                firstPageBtn.onclick = () => loadPesertaData(1);
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
                pageBtn.onclick = () => loadPesertaData(i);
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
                lastPageBtn.onclick = () => loadPesertaData(totalPagesValue);
                pageNumbers.appendChild(lastPageBtn);
            }
        }

        async function viewDetail(id) {
            try {
                const response = await AwaitFetchApi(`admin/peserta/${id}`, 'GET');
                if (response?.data) {
                    const peserta = response.data;
                    currentPesertaId = peserta.id;

                    // Populate basic data
                    document.getElementById('detail-id').textContent = peserta.id || '-';
                    document.getElementById('detail-nisn').textContent = peserta.nisn || '-';
                    document.getElementById('detail-nama').textContent = peserta.nama || '-';
                    document.getElementById('detail-ttl').textContent =
                        `${peserta.tempat_lahir || '-'}, ${peserta.tanggal_lahir ? new Date(peserta.tanggal_lahir).toLocaleDateString() : '-'}`;
                    document.getElementById('detail-gender').textContent = peserta.jenis_kelamin || '-';
                    document.getElementById('detail-alamat').textContent = peserta.alamat || '-';
                    document.getElementById('detail-telp').textContent = peserta.no_telp || '-';

                    // Set current NIS value in the input field
                    document.getElementById('detail-nis-input').value = peserta.nis || '';

                    // Set the status dropdown value
                    const statusDropdown = document.getElementById('detail-status');
                    if (peserta.status) {
                        statusDropdown.value = peserta.status.toLowerCase();
                    } else {
                        statusDropdown.value = 'Proses'; // Default value
                    }

                    // Populate education data
                    document.getElementById('detail-jenjang').textContent = peserta.jenjang_sekolah || '-';

                    // Handle jurusan1 data (could be object with nested properties)
                    if (peserta.jurusan1) {
                        if (typeof peserta.jurusan1 === 'object') {
                            document.getElementById('detail-jurusan1').textContent = peserta.jurusan1.jurusan || '-';
                        } else {
                            document.getElementById('detail-jurusan1').textContent = peserta.jurusan1 || '-';
                        }
                    } else {
                        document.getElementById('detail-jurusan1').textContent = '-';
                    }

                    // Populate parent data if available
                    if (peserta.biodata_ortu) {
                        document.getElementById('detail-nama-ayah').textContent = peserta.biodata_ortu.nama_ayah || '-';
                        document.getElementById('detail-nama-ibu').textContent = peserta.biodata_ortu.nama_ibu || '-';

                        // Handle pekerjaan_ayah (could be object with nested properties)
                        if (peserta.biodata_ortu.pekerjaan_ayah) {
                            if (typeof peserta.biodata_ortu.pekerjaan_ayah === 'object') {
                                document.getElementById('detail-pekerjaan-ayah').textContent = peserta.biodata_ortu
                                    .pekerjaan_ayah.pekerjaan || '-';
                            } else {
                                document.getElementById('detail-pekerjaan-ayah').textContent = peserta.biodata_ortu
                                    .pekerjaan_ayah || '-';
                            }
                        } else {
                            document.getElementById('detail-pekerjaan-ayah').textContent = '-';
                        }

                        // Handle pekerjaan_ibu (could be object with nested properties)
                        if (peserta.biodata_ortu.pekerjaan_ibu) {
                            if (typeof peserta.biodata_ortu.pekerjaan_ibu === 'object') {
                                document.getElementById('detail-pekerjaan-ibu').textContent = peserta.biodata_ortu
                                    .pekerjaan_ibu.pekerjaan || '-';
                            } else {
                                document.getElementById('detail-pekerjaan-ibu').textContent = peserta.biodata_ortu
                                    .pekerjaan_ibu || '-';
                            }
                        } else {
                            document.getElementById('detail-pekerjaan-ibu').textContent = '-';
                        }

                        // Handle penghasilan_ortu (could be object with nested properties)
                        if (peserta.biodata_ortu.penghasilan_ortu) {
                            if (typeof peserta.biodata_ortu.penghasilan_ortu === 'object') {
                                document.getElementById('detail-penghasilan-ortu').textContent = peserta.biodata_ortu
                                    .penghasilan_ortu.penghasilan || '-';
                            } else {
                                document.getElementById('detail-penghasilan-ortu').textContent = peserta.biodata_ortu
                                    .penghasilan_ortu || '-';
                            }
                        } else {
                            document.getElementById('detail-penghasilan-ortu').textContent = '-';
                        }
                    } else {
                        // If no parent data, display default values
                        document.getElementById('detail-nama-ayah').textContent = '-';
                        document.getElementById('detail-nama-ibu').textContent = '-';
                        document.getElementById('detail-pekerjaan-ayah').textContent = '-';
                        document.getElementById('detail-pekerjaan-ibu').textContent = '-';
                        document.getElementById('detail-penghasilan-ortu').textContent = '-';
                    }

                    // User data
                    document.getElementById('detail-user-id').textContent = peserta.user_id || '-';

                    // Dates
                    document.getElementById('detail-created').textContent = peserta.created_at ? new Date(peserta
                        .created_at).toLocaleString() : '-';
                    document.getElementById('detail-updated').textContent = peserta.updated_at ? new Date(peserta
                        .updated_at).toLocaleString() : '-';

                    // Load berkas for this peserta
                    loadBerkasPeserta(peserta.id);

                    // Show the modal using the global function
                    openModal('detailModal');
                } else {
                    showNotification('Gagal memuat detail peserta', 'error');
                }
            } catch (error) {
                print.error('Error fetching peserta details:', error);
                showNotification('Terjadi kesalahan saat memuat detail peserta', 'error');
            }
        }

        async function deletePeserta(id) {
            const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus peserta ini?');

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await AwaitFetchApi(`admin/peserta/${id}`, 'DELETE');
                if (response.meta?.code === 200) {
                    showNotification('Peserta berhasil dihapus', 'success');
                    loadPesertaData(currentPage);
                } else {
                    showNotification(`Gagal menghapus peserta: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error deleting peserta:', error);
                showNotification('Terjadi kesalahan saat menghapus peserta', 'error');
            }
        }

        async function updatePesertaStatus() {
            if (!currentPesertaId) {
                showNotification('ID Peserta tidak valid', 'error');
                return;
            }

            const status = document.getElementById('detail-status').value;

            try {
                const response = await AwaitFetchApi(`admin/peserta/${currentPesertaId}`, 'PUT', {
                    status
                });

                if (response.meta?.code === 200) {
                    showNotification('Status peserta berhasil diperbarui', 'success');
                    loadPesertaData(currentPage); // Refresh the table
                } else {
                    showNotification(`Gagal memperbarui status: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error updating peserta status:', error);
                showNotification('Terjadi kesalahan saat memperbarui status peserta', 'error');
            }
        }

        // Form submission
        document.getElementById('pesertaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await AwaitFetchApi('admin/peserta', 'POST', data);
                if (response.meta?.code === 201) {
                    showNotification('Peserta berhasil ditambahkan', 'success');
                    closeModal('tambahModal');
                    loadPesertaData(currentPage);
                } else {
                    showNotification(`Gagal menambahkan peserta: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error adding peserta:', error);
                showNotification('Terjadi kesalahan saat menambahkan peserta', 'error');
            }
        });

        // Helper function to sort peserta data by penghasilan_ortu
        function sortPesertaByPenghasilanOrtu(data, direction) {
            return [...data].sort((a, b) => {
                // Extract penghasilan values
                const valueA = getPenghasilanValue(a);
                const valueB = getPenghasilanValue(b);

                // Compare for sorting
                if (valueA === '-' && valueB !== '-') return direction === 'asc' ? -1 : 1;
                if (valueA !== '-' && valueB === '-') return direction === 'asc' ? 1 : -1;
                if (valueA === '-' && valueB === '-') return 0;

                // Simple string comparison
                const comparison = valueA.localeCompare(valueB);
                return direction === 'asc' ? comparison : -comparison;
            });
        }

        // Helper to extract penghasilan value from a peserta object
        function getPenghasilanValue(peserta) {
            if (peserta.penghasilan_ortu) {
                if (typeof peserta.penghasilan_ortu === 'object') {
                    return peserta.penghasilan_ortu.penghasilan || '-';
                }
                return peserta.penghasilan_ortu;
            }

            if (peserta.biodata_ortu && peserta.biodata_ortu.penghasilan_ortu) {
                if (typeof peserta.biodata_ortu.penghasilan_ortu === 'object') {
                    return peserta.biodata_ortu.penghasilan_ortu.penghasilan || '-';
                }
                return peserta.biodata_ortu.penghasilan_ortu;
            }

            return '-';
        }

        async function loadTrashData(page = 1) {
            try {
                const url = `admin/pesertas/trash?page=${page}&per_page=${perPage}`;
                const response = await AwaitFetchApi(url, 'GET');

                if (response?.data) {
                    renderTrashTable(response.data);
                    updateTrashPagination(response.pagination);
                }
            } catch (error) {
                print.error('Error loading trash data:', error);
                showNotification('Gagal memuat data peserta terhapus', 'error');
            }
        }

        function renderTrashTable(pesertas) {
            const tbody = document.getElementById('trashTableBody');
            tbody.innerHTML = '';

            if (pesertas.length === 0) {
                const emptyRow = `
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data peserta terhapus yang ditemukan
                </td>
            </tr>
        `;
                tbody.innerHTML = emptyRow;
                return;
            }

            pesertas.forEach((peserta) => {
                const row = `
            <tr>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.id}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.nisn || '-'}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">${peserta.nama}</td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                        ${peserta.jenjang_sekolah || '-'}
                    </span>
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    ${peserta.deleted_at ? new Date(peserta.deleted_at).toLocaleString() : '-'}
                </td>
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <button class="text-green-600 hover:text-green-900 flex items-center" onclick="restorePeserta(${peserta.id})">
                        <i class="fas fa-trash-restore mr-1"></i> 
                        <span>Restore</span>
                    </button>
                </td>
            </tr>
        `;
                tbody.innerHTML += row;
            });
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
                pageBtn.className =
                    `px-3 py-1 rounded border ${currentPageValue === i ? 'bg-blue-500 text-white' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'}`;
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

        async function restorePeserta(id) {
            try {
                const result = await showDeleteConfirmation('Apakah Anda yakin ingin memulihkan peserta ini?',
                    'Ya, Pulihkan', 'Batal');

                if (!result.isConfirmed) {
                    return;
                }

                const response = await AwaitFetchApi(`admin/peserta/${id}/restore`, 'PUT');

                if (response.meta?.code === 200) {
                    showNotification('Peserta berhasil dipulihkan', 'success');
                    loadTrashData(trashCurrentPage); // Refresh trash data
                    loadPesertaData(currentPage); // Refresh main table
                } else {
                    showNotification(`Gagal memulihkan peserta: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error restoring peserta:', error);
                showNotification('Terjadi kesalahan saat memulihkan peserta', 'error');
            }
        }

        // Add event listener to load trash data when modal opens
        document.addEventListener('click', function(e) {
            if (e.target && e.target.closest('[onclick="openModal(\'trashModal\')"]')) {
                loadTrashData();
            }
        });

        // Helper function for showing confirmation dialogs with custom buttons
        async function showConfirmation(message, confirmText = 'Ya', cancelText = 'Batal') {
            return await Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText,
                cancelButtonText: cancelText
            });
        }

        async function loadBerkasPeserta(pesertaId) {
            // Reset berkas container state
            document.getElementById('berkas-loading').classList.remove('hidden');
            document.getElementById('berkas-list').classList.add('hidden');
            document.getElementById('berkas-empty').classList.add('hidden');

            try {
                const response = await AwaitFetchApi(`admin/berkas/peserta/${pesertaId}`, 'GET');

                document.getElementById('berkas-loading').classList.add('hidden');

                if (response?.data && Array.isArray(response.data) && response.data.length > 0) {
                    const berkasList = document.getElementById('berkas-list');
                    berkasList.innerHTML = '';
                    berkasList.classList.remove('hidden');

                    response.data.forEach(berkas => {
                        const berkasItem = document.createElement('div');
                        berkasItem.className = 'p-3 bg-gray-50 rounded-lg flex justify-between items-center';

                        const berkasName = document.createElement('div');
                        berkasName.className = 'flex items-center gap-2';

                        // Determine file icon based on file type/extension
                        const fileUrl = berkas.url_file || '';
                        const fileExt = fileUrl ? fileUrl.split('.').pop().toLowerCase() : '';
                        let fileIcon = 'fa-file';

                        if (['pdf'].includes(fileExt)) {
                            fileIcon = 'fa-file-pdf';
                        } else if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                            fileIcon = 'fa-file-image';
                        } else if (['doc', 'docx'].includes(fileExt)) {
                            fileIcon = 'fa-file-word';
                        } else if (['xls', 'xlsx'].includes(fileExt)) {
                            fileIcon = 'fa-file-excel';
                        }

                        berkasName.innerHTML = `
                            <i class="fas ${fileIcon} text-blue-500"></i>
                            <span>${berkas.nama_file || 'Berkas'}</span>
                        `;

                        const berkasActions = document.createElement('div');
                        berkasActions.className = 'flex gap-2';

                        // View button
                        if (berkas.url_file) {
                            const viewBtn = document.createElement('a');
                            viewBtn.href = berkas.url_file;
                            viewBtn.target = '_blank';
                            viewBtn.className =
                                'px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm';
                            viewBtn.innerHTML = '<i class="fas fa-eye mr-1"></i> Lihat';
                            berkasActions.appendChild(viewBtn);
                        }

                        berkasItem.appendChild(berkasName);
                        berkasItem.appendChild(berkasActions);
                        berkasList.appendChild(berkasItem);
                    });
                } else {
                    document.getElementById('berkas-empty').classList.remove('hidden');
                }
            } catch (error) {
                print.error('Error fetching berkas data:', error);
                document.getElementById('berkas-loading').classList.add('hidden');
                document.getElementById('berkas-empty').classList.remove('hidden');
                document.getElementById('berkas-empty').textContent = 'Gagal memuat data berkas.';
            }
        }

        async function updateNisPeserta() {
            if (!currentPesertaId) {
                showNotification('ID Peserta tidak valid', 'error');
                return;
            }

            const nis = document.getElementById('detail-nis-input').value.trim();

            if (!nis) {
                showNotification('NIS tidak boleh kosong', 'error');
                return;
            }

            try {
                const response = await AwaitFetchApi(`admin/peserta/nis/${currentPesertaId}`, 'PUT', {
                    nis
                });

                if (response.meta?.code === 200) {
                    showNotification('NIS peserta berhasil diperbarui', 'success');
                    loadPesertaData(currentPage); // Refresh the table
                } else {
                    showNotification(`Gagal memperbarui NIS: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error updating peserta NIS:', error);
                showNotification('Terjadi kesalahan saat memperbarui NIS peserta', 'error');
            }
        }
    </script>
@endsection

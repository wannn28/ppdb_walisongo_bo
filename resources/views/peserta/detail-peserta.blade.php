@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-2xl font-bold mb-4 md:mb-0">Detail Peserta PPDB</h1>
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
                <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg flex items-center justify-center"
                    onclick="exportToExcel()">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center justify-center"
                    onclick="openModal('trashModal')">
                    <i class="fas fa-trash mr-2"></i> Trash
                </button>
            </div>
        </div>

        <!-- Filter Controls -->
        <x-filter resetFunction="resetFilters">
            <x-filter-text 
                id="searchInput" 
                label="Cari Peserta" 
                placeholder="Cari nama, nisn..." 
                onChangeFunction="updateSearchFilter" />
            
            <x-filter-select 
                id="statusFilter" 
                label="Status" 
                :options="[''=>'Semua Status', 'diterima'=>'Diterima', 'ditolak'=>'Ditolak', 'diproses'=>'Diproses']" 
                onChangeFunction="updateStatusFilter" />
            
            <x-filter-select 
                id="jenjangFilter" 
                label="Jenjang" 
                :options="[''=>'Semua Jenjang', 'SD'=>'SD', 'SMP'=>'SMP', 'SMA'=>'SMA', 'SMP 1'=>'SMP 1', 'SMP 2'=>'SMP 2']" 
                onChangeFunction="updateJenjangFilter" />
                
            <x-filter-select 
                id="angkatanFilter" 
                label="Angkatan" 
                :options="[''=>'Semua Angkatan', '2023'=>'2023', '2024'=>'2024', '2025'=>'2025']" 
                onChangeFunction="updateAngkatanFilter" />
            
            <x-filter-date-range 
                startId="startDate" 
                endId="endDate" 
                label="Tanggal Daftar" 
                onStartChangeFunction="updateStartDateFilter" 
                onEndChangeFunction="updateEndDateFilter" />
        </x-filter>
        
        <div class="bg-white rounded-lg shadow-md overflow-x-auto mt-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi</th>
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
                        <x-sortable-header column="status" label="Verifikasi Berkas"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
                        <x-sortable-header column="penghasilan_ortu" label="Penghasilan Ortu"
                            additionalClasses="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm" />
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
                <div class="flex items-center gap-2">
                    <button onclick="exportToPdf()" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-file-pdf text-lg"></i> <span class="text-sm">Export PDF</span>
                    </button>
                    <button data-close-modal="detailModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div id="pdf-content">
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
                                <label class="block text-sm font-medium text-gray-700">Verifikasi Berkas</label>
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
                                Aksi
                            </th>
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

    <!-- Modal Edit Peserta -->
    <div id="editModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Data Peserta</h3>
                <button data-close-modal="editModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editPesertaForm" class="space-y-6">
                <input type="hidden" id="edit-id">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Data Pribadi -->
                    <div>
                        <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Data Pribadi</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NISN</label>
                                <input type="text" id="edit-nisn" name="nisn" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIS</label>
                                <input type="text" id="edit-nis" name="nis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="edit-nama" name="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Lahir</label>
                                <input type="text" id="edit-tempat-lahir" name="tempat_lahir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" id="edit-tanggal-lahir" name="tanggal_lahir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                <select id="edit-jenis-kelamin" name="jenis_kelamin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea id="edit-alamat" name="alamat" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="text" id="edit-no-telp" name="no_telp" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pendidikan -->
                    <div>
                        <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Informasi Pendidikan</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenjang Sekolah</label>
                                <select id="edit-jenjang" name="jenjang_sekolah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA">SMA</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Angkatan</label>
                                <input type="text" id="edit-angkatan" name="angkatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="edit-status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    <option value="Diproses">Diproses</option>
                                    <option value="Diterima">Diterima</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div>
                        <h4 class="font-semibold mb-4 text-blue-700 border-b pb-2">Data Orang Tua</h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ayah</label>
                                <input type="text" id="edit-nama-ayah" name="nama_ayah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pekerjaan Ayah</label>
                                <input type="text" id="edit-pekerjaan-ayah" name="pekerjaan_ayah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Ibu</label>
                                <input type="text" id="edit-nama-ibu" name="nama_ibu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pekerjaan Ibu</label>
                                <input type="text" id="edit-pekerjaan-ibu" name="pekerjaan_ibu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Penghasilan Orang Tua</label>
                                <input type="text" id="edit-penghasilan-ortu" name="penghasilan_ortu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" data-close-modal="editModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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
        let jenjang_sekolah = localStorage.getItem('jenjang_sekolah');
        let filters = {
            search: '',
            status: '',
            jenjang_sekolah: jenjang_sekolah,
            angkatan: '',
            start_date: '',
            end_date: ''
        };

        // Filter functions
        function updateSearchFilter(value) {
            filters.search = value;
            currentPage = 1; // Reset to first page when filtering
            loadPesertaData();
        }
        
        function updateStatusFilter(value) {
            filters.status = value;
            currentPage = 1;
            loadPesertaData();
        }
        
        function updateJenjangFilter(value) {
            document.getElementById('jenjangFilter').value = value;
            filters.jenjang_sekolah = value;
            currentPage = 1;
            loadPesertaData();
        }
       
        function updateAngkatanFilter(value) {
            filters.angkatan = value;
            currentPage = 1;
            loadPesertaData();
        }
        
        function updateStartDateFilter(value) {
            filters.start_date = value;
            currentPage = 1;
            loadPesertaData();
        }
        
        function updateEndDateFilter(value) {
            filters.end_date = value;
            currentPage = 1;
            loadPesertaData();
        }
        
        function resetFilters() {
            filters = {
                search: '',
                status: '',
                jenjang_sekolah: jenjang_sekolah,
                angkatan: '',
                start_date: '',
                end_date: ''
            };
            
            // Reset form inputs
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('jenjangFilter').value = jenjang_sekolah || '';
            document.getElementById('angkatanFilter').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
            
            currentPage = 1;
            loadPesertaData();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateJenjangFilter(jenjang_sekolah || 'Semua Jenjang') 
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

                // Construct the URL with all filters
                const params = new URLSearchParams({
                    page: page,
                    per_page: perPage,
                    sort_by: sortField,
                    order_by: sortDirection
                });

                // Add filters to params if they have values
                if (filters.search) params.append('search', filters.search);
                if (filters.status) params.append('status', filters.status);
                if (filters.jenjang_sekolah) params.append('jenjang_sekolah', filters.jenjang_sekolah);
                if (filters.angkatan) params.append('angkatan', filters.angkatan);
                if (filters.start_date) params.append('start_date', filters.start_date);
                if (filters.end_date) params.append('end_date', filters.end_date);

                const url = `admin/pesertas?${params.toString()}`;
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
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button class="text-blue-600 hover:text-blue-900" onclick="viewDetail(${peserta.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="text-yellow-600 hover:text-yellow-900" onclick="editPeserta(${peserta.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" onclick="deletePeserta(${peserta.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
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
                    showNotification('Verifikasi berkas peserta berhasil diperbarui', 'success');
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
                <td class="px-2 sm:px-4 py-2 sm:py-4 whitespace-nowrap text-xs sm:text-sm">
                    <button class="text-green-600 hover:text-green-900 flex items-center" onclick="restorePeserta(${peserta.id})">
                        <i class="fas fa-trash-restore mr-1"></i> 
                        <span>Restore</span>
                    </button>
                </td>
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

        // Export to Excel function
        async function exportToExcel() {
            try {
                showNotification('Mempersiapkan data untuk export...', 'info');
                
                // Get current filters - create a copy
                const exportFilters = {...filters};
                
                // Make sure to only export peserta with status 'diterima' if not already filtered
                if (!exportFilters.status) {
                    exportFilters.status = 'diterima';
                }
                
                // Construct the URL with all filters but with max per_page
                const params = new URLSearchParams({
                    page: 1,
                    per_page: 1000, // Set a high value to get all data in one request
                    sort_by: sortBy,
                    order_by: sortDirection
                });
                
                // Add filters to params if they have values
                if (exportFilters.search) params.append('search', exportFilters.search);
                if (exportFilters.status) params.append('status', exportFilters.status);
                if (exportFilters.jenjang_sekolah) params.append('jenjang_sekolah', exportFilters.jenjang_sekolah);
                if (exportFilters.angkatan) params.append('angkatan', exportFilters.angkatan);
                if (exportFilters.start_date) params.append('start_date', exportFilters.start_date);
                if (exportFilters.end_date) params.append('end_date', exportFilters.end_date);
                
                const url = `admin/pesertas?${params.toString()}`;
                const response = await AwaitFetchApi(url, 'GET');
                
                if (!response?.data || response.data.length === 0) {
                    showNotification('Tidak ada data untuk di-export', 'warning');
                    return;
                }
                
                // Format data for Excel
                const exportData = response.data.map(peserta => {
                    // Determine penghasilan ortu
                    let penghasilanOrtu = '';
                    if (peserta.penghasilan_ortu) {
                        if (typeof peserta.penghasilan_ortu === 'object') {
                            penghasilanOrtu = peserta.penghasilan_ortu.penghasilan || '';
                        } else {
                            penghasilanOrtu = peserta.penghasilan_ortu;
                        }
                    } else if (peserta.biodata_ortu && peserta.biodata_ortu.penghasilan_ortu) {
                        if (typeof peserta.biodata_ortu.penghasilan_ortu === 'object') {
                            penghasilanOrtu = peserta.biodata_ortu.penghasilan_ortu.penghasilan || '';
                        } else {
                            penghasilanOrtu = peserta.biodata_ortu.penghasilan_ortu;
                        }
                    }
                    
                    // Determine nama ayah
                    let namaAyah = '';
                    if (peserta.biodata_ortu && peserta.biodata_ortu.nama_ayah) {
                        namaAyah = peserta.biodata_ortu.nama_ayah;
                    }
                    
                    // Determine nama ibu
                    let namaIbu = '';
                    if (peserta.biodata_ortu && peserta.biodata_ortu.nama_ibu) {
                        namaIbu = peserta.biodata_ortu.nama_ibu;
                    }
                    
                    return {
                        'ID': peserta.id,
                        'NISN': peserta.nisn || '',
                        'NIS': peserta.nis || '',
                        'Nama Lengkap': peserta.nama || '',
                        'Tempat Lahir': peserta.tempat_lahir || '',
                        'Tanggal Lahir': peserta.tanggal_lahir ? new Date(peserta.tanggal_lahir).toLocaleDateString() : '',
                        'Jenis Kelamin': peserta.jenis_kelamin || '',
                        'Alamat': peserta.alamat || '',
                        'No. Telepon': peserta.no_telp || '',
                        'Jenjang': peserta.jenjang_sekolah || '',
                        'Angkatan': peserta.angkatan || '',
                        'Status': peserta.status || '',
                        'Nama Ayah': namaAyah,
                        'Nama Ibu': namaIbu,
                        'Penghasilan Ortu': penghasilanOrtu,
                    };
                });
                
                // Convert to worksheet
                const worksheet = XLSX.utils.json_to_sheet(exportData);
                
                // Create workbook and add the worksheet
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Peserta PPDB');
                
                // Generate Excel file
                const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
                
                // Save to file
                const today = new Date();
                const dateStr = `${today.getFullYear()}-${(today.getMonth()+1).toString().padStart(2, '0')}-${today.getDate().toString().padStart(2, '0')}`;
                
                // Create filename with filters
                let filename = `Peserta_PPDB_${dateStr}`;
                if (exportFilters.status) filename += `_${exportFilters.status}`;
                if (exportFilters.jenjang_sekolah) filename += `_${exportFilters.jenjang_sekolah}`;
                if (exportFilters.angkatan) filename += `_${exportFilters.angkatan}`;
                filename += '.xlsx';
                
                saveExcelFile(excelBuffer, filename);
                
                showNotification('Excel berhasil di-export!', 'success');
            } catch (error) {
                print.error('Error exporting to Excel:', error);
                showNotification('Gagal mengexport data ke Excel', 'error');
            }
        }
        
        // Helper function to save Excel file
        function saveExcelFile(buffer, filename) {
            const blob = new Blob([buffer], { type: 'application/octet-stream' });
            
            // Create download link
            const url = window.URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = filename;
            link.click();
            
            // Clean up
            setTimeout(() => {
                window.URL.revokeObjectURL(url);
            }, 100);
        }

        // Export to PDF function
        async function exportToPdf() {
            if (!currentPesertaId) {
                showNotification('Data peserta tidak ditemukan', 'error');
                return;
            }
            
            try {
                showNotification('Mempersiapkan PDF...', 'info');
                
                // Make sure jspdf is properly loaded
                if (!window.jspdf || !window.jspdf.jsPDF) {
                    throw new Error('Library jsPDF tidak tersedia');
                }
                
                // Initialize jsPDF with proper namespace
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                
                // Set document properties
                doc.setProperties({
                    title: 'Data Peserta PPDB',
                    subject: 'Informasi Siswa',
                    author: 'PPDB Walisongo',
                    creator: 'PPDB Walisongo System'
                });
                
                // Add Fonts
                doc.setFont('helvetica', 'bold');
                
                // Add Header
                doc.setFontSize(18);
                doc.setTextColor(0, 51, 102);
                const pesertaName = document.getElementById('detail-nama').textContent || 'Nama Peserta';
                const pesertaNisn = document.getElementById('detail-nisn').textContent || 'NISN';
                
                doc.text('DATA PESERTA PPDB', 105, 20, { align: 'center' });
                doc.setFontSize(14);
                doc.text(pesertaName, 105, 30, { align: 'center' });
                doc.setFontSize(10);
                doc.text(`NISN: ${pesertaNisn}`, 105, 37, { align: 'center' });
                
                // Draw a line
                doc.setDrawColor(0, 102, 204);
                doc.setLineWidth(0.5);
                doc.line(20, 40, 190, 40);
                
                // Helper function to safely get element text content
                const getElementText = (id) => {
                    const element = document.getElementById(id);
                    return element ? element.textContent || '-' : '-';
                };
                
                // Helper function to safely get element value
                const getElementValue = (id) => {
                    const element = document.getElementById(id);
                    return element ? element.value || '-' : '-';
                };
                
                // Define sections and fields
                const sections = [
                    {
                        title: "DATA PRIBADI",
                        fields: [
                            { label: "Nama Lengkap", value: getElementText('detail-nama') },
                            { label: "NISN", value: getElementText('detail-nisn') },
                            { label: "Tempat, Tanggal Lahir", value: getElementText('detail-ttl') },
                            { label: "Jenis Kelamin", value: getElementText('detail-gender') },
                            { label: "Alamat", value: getElementText('detail-alamat') },
                            { label: "No. Telepon", value: getElementText('detail-telp') },
                            { 
                                label: "Status Verifikasi", 
                                value: (() => {
                                    const el = document.getElementById('detail-status');
                                    return el && el.selectedIndex >= 0 ? el.options[el.selectedIndex].text : '-';
                                })()
                            },
                            { 
                                label: "NIS", 
                                value: getElementValue('detail-nis-input')
                            }
                        ]
                    },
                    {
                        title: "INFORMASI PENDIDIKAN",
                        fields: [
                            { label: "Jenjang Sekolah", value: getElementText('detail-jenjang') },
                            { label: "Pilihan Kelas", value: getElementText('detail-jurusan1') },
                            { label: "ID User", value: getElementText('detail-user-id') }
                        ]
                    },
                    {
                        title: "DATA ORANG TUA",
                        fields: [
                            { label: "Nama Ayah", value: getElementText('detail-nama-ayah') },
                            { label: "Pekerjaan Ayah", value: getElementText('detail-pekerjaan-ayah') },
                            { label: "Nama Ibu", value: getElementText('detail-nama-ibu') },
                            { label: "Pekerjaan Ibu", value: getElementText('detail-pekerjaan-ibu') },
                            { label: "Penghasilan Orang Tua", value: getElementText('detail-penghasilan-ortu') }
                        ]
                    },
                    {
                        title: "INFORMASI TAMBAHAN",
                        fields: [
                            { label: "ID Peserta", value: getElementText('detail-id') },
                            { label: "Terdaftar pada", value: getElementText('detail-created') },
                            { label: "Terakhir diupdate", value: getElementText('detail-updated') }
                        ]
                    }
                ];
                
                // Start position
                let y = 50;
                
                // Define colors and styles
                const titleColor = [0, 51, 153];
                const labelColor = [0, 0, 0];
                const valueColor = [50, 50, 50];
                
                // Add sections
                for (const section of sections) {
                    // Add section title
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(12);
                    doc.setTextColor(titleColor[0], titleColor[1], titleColor[2]);
                    doc.text(section.title, 20, y);
                    
                    // Draw a colored line under the title
                    doc.setDrawColor(0, 102, 204);
                    doc.setLineWidth(0.2);
                    doc.line(20, y + 1, 190, y + 1);
                    
                    y += 8;
                    
                    // Add fields
                    for (const field of section.fields) {
                        // Check if we need a new page
                        if (y > 270) {
                            doc.addPage();
                            y = 20;
                        }
                        
                        doc.setFont('helvetica', 'bold');
                        doc.setFontSize(10);
                        doc.setTextColor(labelColor[0], labelColor[1], labelColor[2]);
                        doc.text(`${field.label}:`, 20, y);
                        
                        doc.setFont('helvetica', 'normal');
                        doc.setTextColor(valueColor[0], valueColor[1], valueColor[2]);
                        
                        // If value is longer than 80 chars, split into multiple lines
                        const value = field.value || '-';
                        if (value.length > 80) {
                            const lines = doc.splitTextToSize(value, 120);
                            for (let i = 0; i < lines.length; i++) {
                                doc.text(lines[i], 70, y + (i * 5));
                                if (i > 0) y += 5;
                            }
                        } else {
                            doc.text(value, 70, y);
                        }
                        
                        y += 7;
                    }
                    
                    y += 5;
                }
                
                // Add Berkas information
                const berkasContainer = document.getElementById('berkas-list');
                if (berkasContainer && !berkasContainer.classList.contains('hidden')) {
                    // Check if we need a new page
                    if (y > 250) {
                        doc.addPage();
                        y = 20;
                    }
                    
                    // Title
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(12);
                    doc.setTextColor(titleColor[0], titleColor[1], titleColor[2]);
                    doc.text("BERKAS PESERTA", 20, y);
                    
                    // Draw a colored line under the title
                    doc.setDrawColor(0, 102, 204);
                    doc.setLineWidth(0.2);
                    doc.line(20, y + 1, 190, y + 1);
                    
                    y += 8;
                    
                    // List berkas
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(10);
                    doc.setTextColor(0, 0, 0);
                    
                    const berkasList = Array.from(berkasContainer.querySelectorAll('div'));
                    for (const item of berkasList) {
                        const span = item.querySelector('span');
                        if (span) {
                            doc.text(`- ${span.textContent || 'Berkas'}`, 20, y);
                            y += 6;
                            
                            // Check if we need a new page
                            if (y > 270) {
                                doc.addPage();
                                y = 20;
                            }
                        }
                    }
                }
                
                // Add footer
                const totalPages = doc.getNumberOfPages();
                for (let i = 1; i <= totalPages; i++) {
                    doc.setPage(i);
                    doc.setFont('helvetica', 'italic');
                    doc.setFontSize(8);
                    doc.setTextColor(100, 100, 100);
                    doc.text(`Dokumen ini dicetak pada ${new Date().toLocaleString()} - Halaman ${i} dari ${totalPages}`, 105, 290, { align: 'center' });
                }
                
                // Save the PDF
                const filename = `PPDB_${pesertaNisn.replace(/[^a-zA-Z0-9]/g, '') || 'peserta'}_${pesertaName.replace(/\s+/g, '_')}.pdf`;
                doc.save(filename);
                
                showNotification('PDF berhasil di-export!', 'success');
            } catch (error) {
                console.error('Error exporting to PDF:', error);
                showNotification('Gagal mengexport data ke PDF: ' + error.message, 'error');
            }
        }

        async function editPeserta(id) {
            try {
                const response = await AwaitFetchApi(`admin/peserta/${id}`, 'GET');
                if (response?.data) {
                    const peserta = response.data;
                    
                    // Set form ID
                    document.getElementById('edit-id').value = peserta.id;
                    
                    // Fill basic data
                    document.getElementById('edit-nisn').value = peserta.nisn || '';
                    document.getElementById('edit-nis').value = peserta.nis || '';
                    document.getElementById('edit-nama').value = peserta.nama || '';
                    document.getElementById('edit-tempat-lahir').value = peserta.tempat_lahir || '';
                    document.getElementById('edit-tanggal-lahir').value = peserta.tanggal_lahir ? new Date(peserta.tanggal_lahir).toISOString().split('T')[0] : '';
                    document.getElementById('edit-jenis-kelamin').value = peserta.jenis_kelamin || 'L';
                    document.getElementById('edit-alamat').value = peserta.alamat || '';
                    document.getElementById('edit-no-telp').value = peserta.no_telp || '';
                    
                    // Fill education data
                    document.getElementById('edit-jenjang').value = peserta.jenjang_sekolah || '';
                    document.getElementById('edit-angkatan').value = peserta.angkatan || '';
                    document.getElementById('edit-status').value = peserta.status || '';
                    
                    // Fill parent data if available
                    if (peserta.biodata_ortu) {
                        document.getElementById('edit-nama-ayah').value = peserta.biodata_ortu.nama_ayah || '';
                        document.getElementById('edit-nama-ibu').value = peserta.biodata_ortu.nama_ibu || '';
                        
                        // Handle pekerjaan_ayah (could be object with nested properties)
                        if (peserta.biodata_ortu.pekerjaan_ayah) {
                            if (typeof peserta.biodata_ortu.pekerjaan_ayah === 'object') {
                                document.getElementById('edit-pekerjaan-ayah').value = peserta.biodata_ortu.pekerjaan_ayah.pekerjaan || '';
                            } else {
                                document.getElementById('edit-pekerjaan-ayah').value = peserta.biodata_ortu.pekerjaan_ayah || '';
                            }
                        }
                        
                        // Handle pekerjaan_ibu (could be object with nested properties)
                        if (peserta.biodata_ortu.pekerjaan_ibu) {
                            if (typeof peserta.biodata_ortu.pekerjaan_ibu === 'object') {
                                document.getElementById('edit-pekerjaan-ibu').value = peserta.biodata_ortu.pekerjaan_ibu.pekerjaan || '';
                            } else {
                                document.getElementById('edit-pekerjaan-ibu').value = peserta.biodata_ortu.pekerjaan_ibu || '';
                            }
                        }
                        
                        // Handle penghasilan_ortu (could be object with nested properties)
                        if (peserta.biodata_ortu.penghasilan_ortu) {
                            if (typeof peserta.biodata_ortu.penghasilan_ortu === 'object') {
                                document.getElementById('edit-penghasilan-ortu').value = peserta.biodata_ortu.penghasilan_ortu.penghasilan || '';
                            } else {
                                document.getElementById('edit-penghasilan-ortu').value = peserta.biodata_ortu.penghasilan_ortu || '';
                            }
                        }
                    }
                    
                    // Show the modal
                    openModal('editModal');
                    
                } else {
                    showNotification('Gagal memuat data peserta', 'error');
                }
            } catch (error) {
                print.error('Error fetching peserta data for edit:', error);
                showNotification('Terjadi kesalahan saat memuat data peserta', 'error');
            }
        }

        // Handle edit form submission
        document.getElementById('editPesertaForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit-id').value;
            if (!id) {
                showNotification('ID Peserta tidak valid', 'error');
                return;
            }
            
            // Gather form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Create nested structure for biodata_ortu
            const biodataOrtu = {
                nama_ayah: data.nama_ayah || '',
                nama_ibu: data.nama_ibu || '',
                pekerjaan_ayah: data.pekerjaan_ayah || '',
                pekerjaan_ibu: data.pekerjaan_ibu || '',
                penghasilan_ortu: data.penghasilan_ortu || ''
            };
            
            // Remove individual parent fields from the main object
            delete data.nama_ayah;
            delete data.nama_ibu;
            delete data.pekerjaan_ayah;
            delete data.pekerjaan_ibu;
            delete data.penghasilan_ortu;
            
            // Add the nested object
            data.biodata_ortu = biodataOrtu;
            
            try {
                // Show loading notification
                showNotification('Menyimpan perubahan...', 'info');
                
                const response = await AwaitFetchApi(`admin/peserta/${id}`, 'PUT', data);
                
                if (response.meta?.code === 200) {
                    showNotification('Data peserta berhasil diperbarui', 'success');
                    closeModal('editModal');
                    loadPesertaData(currentPage); // Refresh the table
                    
                    // If the current detail modal is open and showing this peserta, refresh it
                    if (currentPesertaId === parseInt(id)) {
                        viewDetail(id);
                    }
                }
            } catch (error) {
                print.error('Error updating peserta data:', error);
                showNotification('Terjadi kesalahan saat memperbarui data peserta', 'error');
            }
        });
    </script>
    
    <!-- Add SheetJS library for Excel export -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    
    <!-- Add jsPDF library for direct PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection

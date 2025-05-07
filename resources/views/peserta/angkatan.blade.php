@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Angkatan</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center"
                onclick="openModal('tambahModal')">
                <i class="fas fa-plus mr-2"></i> Tambah Angkatan
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Angkatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="angkatanTableBody">
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Angkatan -->
    <div id="tambahModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tambah Angkatan Baru</h3>
                <form id="angkatanForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Angkatan</label>
                        <input type="text" name="angkatan"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
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

    <!-- Modal Edit Angkatan -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full modal-container">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Edit Angkatan</h3>
                <form id="editAngkatanForm">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Angkatan</label>
                        <input type="text" name="angkatan" id="edit-angkatan"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" data-close-modal="editModal"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentAngkatanId = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadAngkatanData();
        });

        async function loadAngkatanData() {
            try {
                const response = await AwaitFetchApi('admin/angkatan', 'GET');
                if (response?.data) {
                    renderAngkatanTable(response.data);
                }
            } catch (error) {
                print.error('Error loading angkatan data:', error);
                showNotification('Gagal memuat data angkatan', 'error');
            }
        }

        function renderAngkatanTable(angkatans) {
            const tbody = document.getElementById('angkatanTableBody');
            tbody.innerHTML = '';

            if (angkatans.length === 0) {
                const emptyRow = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data angkatan yang ditemukan
                        </td>
                    </tr>
                `;
                tbody.innerHTML = emptyRow;
                return;
            }

            angkatans.forEach((angkatan) => {
                const row = `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">${angkatan.id}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">
                                ${angkatan.angkatan}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${new Date(angkatan.created_at).toLocaleString()}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${new Date(angkatan.updated_at).toLocaleString()}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="editAngkatan(${angkatan.id}, '${angkatan.angkatan}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" onclick="deleteAngkatan(${angkatan.id})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Form submission for adding new angkatan
        document.getElementById('angkatanForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await AwaitFetchApi('admin/angkatan', 'POST', data);
                if (response.meta?.code === 200) {
                    showNotification('Angkatan berhasil ditambahkan', 'success');
                    closeModal('tambahModal');
                    loadAngkatanData();
                    this.reset();
                } else {
                    showNotification(`Gagal menambahkan angkatan: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error adding angkatan:', error);
                showNotification('Terjadi kesalahan saat menambahkan angkatan', 'error');
            }
        });

        // Form submission for editing angkatan
        document.getElementById('editAngkatanForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await AwaitFetchApi(`admin/angkatan/${currentAngkatanId}`, 'PUT', data);
                if (response.meta?.code === 200) {
                    showNotification('Angkatan berhasil diperbarui', 'success');
                    closeModal('editModal');
                    loadAngkatanData();
                } else {
                    showNotification(`Gagal memperbarui angkatan: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error updating angkatan:', error);
                showNotification('Terjadi kesalahan saat memperbarui angkatan', 'error');
            }
        });

        function editAngkatan(id, angkatan) {
            currentAngkatanId = id;
            document.getElementById('edit-id').value = id;
            document.getElementById('edit-angkatan').value = angkatan;
            openModal('editModal');
        }

        async function deleteAngkatan(id) {
            const result = await showDeleteConfirmation('Apakah Anda yakin ingin menghapus angkatan ini?');

            if (!result.isConfirmed) {
                return;
            }

            try {
                const response = await AwaitFetchApi(`admin/angkatan/${id}`, 'DELETE');
                if (response.meta?.code === 200) {
                    showNotification('Angkatan berhasil dihapus', 'success');
                    loadAngkatanData();
                } else {
                    showNotification(`Gagal menghapus angkatan: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                print.error('Error deleting angkatan:', error);
                showNotification('Terjadi kesalahan saat menghapus angkatan', 'error');
            }
        }
    </script>
@endsection 
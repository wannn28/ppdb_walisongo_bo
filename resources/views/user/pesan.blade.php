@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Pesan</h1>
            <div>
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
@endsection

@push('scripts')
    <script>
        let currentPesanId = null;

        document.addEventListener('DOMContentLoaded', () => {
            loadPesan();

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
        });

        async function loadPesan() {
            try {
                const response = await AwaitFetchApi('admin/pesan', 'GET');
                print.log('API Response - Pesan:', response);

                const tableBody = document.getElementById('pesanTableBody');
                tableBody.innerHTML = '';

                if (!response.data || response.data.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = `
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Tidak ada data pesan
                    </td>
                `;
                    tableBody.appendChild(emptyRow);
                    return;
                }

                // Check if data is in response.data or response.data.data based on API structure
                const pesanList = Array.isArray(response.data) ? response.data : (response.data.data || []);

                pesanList.forEach((pesan, index) => {
                    const row = document.createElement('tr');
                    const statusClass = pesan.is_read ? 'bg-green-100 text-green-800' :
                        'bg-yellow-100 text-yellow-800';
                    const statusText = pesan.is_read ? 'Dibaca' : 'Belum dibaca';

                    row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
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
                showAlert('Terjadi kesalahan saat memuat data pesan', 'error');
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
                    showAlert(response.meta?.message || 'Gagal memuat detail pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showAlert('Terjadi kesalahan saat memuat detail pesan', 'error');
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
                    showAlert(response.meta?.message || 'Gagal memuat detail pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showAlert('Terjadi kesalahan saat memuat detail pesan', 'error');
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
                    showAlert(response.meta.message || 'Pesan berhasil dihapus', 'success');
                    loadPesan();
                } else {
                    showAlert(response.meta?.message || 'Gagal menghapus pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus pesan', 'error');
            }
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const id = this.getAttribute('data-id');
            const user_id = document.getElementById('user_id').value;
            const judul = document.getElementById('judul').value;
            const deskripsi = document.getElementById('deskripsi').value;

            if (!judul || !deskripsi) {
                showAlert('Judul dan deskripsi harus diisi', 'error');
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
                        showAlert('User ID harus diisi', 'error');
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
                    showAlert(response.meta.message || 'Pesan berhasil disimpan', 'success');
                    closeModal('pesanModal');
                    loadPesan();
                } else {
                    showAlert(response.meta?.message || 'Gagal menyimpan pesan', 'error');
                }
            } catch (error) {
                print.error('Error:', error);
                showAlert('Terjadi kesalahan saat menyimpan pesan', 'error');
            }
        }
    </script>
@endpush

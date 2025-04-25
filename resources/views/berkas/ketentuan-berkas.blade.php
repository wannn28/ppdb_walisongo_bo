@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Ketentuan Berkas</h1>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center"
                onclick="openModal()">
                <i class="fas fa-plus mr-2"></i> Tambah Ketentuan Berkas
            </button>
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
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">1</td>
                        <td class="px-6 py-4 whitespace-nowrap">Kartu Keluarga</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800 mr-1">SD</span>
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 mr-1">SMP</span>
                            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">SMA</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Ya
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="editBerkas(1)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" onclick="deleteBerkas(1)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">2</td>
                        <td class="px-6 py-4 whitespace-nowrap">Ijazah</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 mr-1">SMP</span>
                            <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-800">SMA</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Ya
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3" onclick="editBerkas(2)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="text-red-600 hover:text-red-900" onclick="deleteBerkas(2)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah/Edit Ketentuan Berkas -->
    <div id="berkasModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
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
                        <button type="button" onclick="closeModal()"
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

    <script>
        // Fungsi untuk memuat semua ketentuan berkas
        async function loadKetentuanBerkas() {
            try {
                const response = await AwaitFetchApi('admin/ketentuan-berkas', 'GET');
                if (response.meta?.code === 200) {
                    // Perbaikan path data yang benar
                    renderKetentuanBerkas(response.data || {}); 
                } else {
                    showAlert('Gagal memuat ketentuan berkas: ' + response.meta?.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat memuat ketentuan berkas', 'error');
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
                const jenjangArray = jenjang.split(',').filter(j => ['SD', 'SMP', 'SMA'].includes(j));

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

        // Perbaikan 3: Update fungsi editBerkas
        async function editBerkas(id) {
            try {
                const response = await AwaitFetchApi(`admin/ketentuan-berkas/${id}`, 'GET');
                if (response.meta?.code === 200) {
                    const data = response.data;
                    document.getElementById('namaBerkas').value = data.nama;
                    document.querySelectorAll('input[name="jenjang[]"]').forEach(checkbox => {
                        checkbox.checked = data.jenjang_sekolah.split(',').includes(checkbox.value);
                    });
                    document.querySelector(`input[name="required"][value="${data.is_required ? 1 : 0}"]`).checked =
                    true;
                    document.getElementById('berkasForm').setAttribute('data-id', id);
                    document.getElementById('modalTitle').textContent = 'Edit Ketentuan Berkas';
                    openModal();
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat mengambil data berkas', 'error');
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
                    showAlert(response.meta.message || 'Ketentuan berkas berhasil dihapus', 'success');
                    loadKetentuanBerkas();
                } else {
                    showAlert(response.meta?.message || 'Gagal menghapus ketentuan berkas', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus ketentuan berkas', 'error');
            }
        }

        // Event listener untuk form submit
        document.getElementById('berkasForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const id = this.getAttribute('data-id');
            
            // Gunakan endpoint dan method yang sama untuk create dan update
            const endpoint = 'admin/ketentuan-berkas';
            const method = 'POST';
            
            // Tambahkan ID ke data jika ada
            const data = {
                nama: formData.get('namaBerkas'),
                jenjang_sekolah: formData.getAll('jenjang[]').join(','),
                is_required: formData.get('required') === '1' ? 1 : 0
            };
            
            if(id) data.id = id;

            try {
                const response = await AwaitFetchApi(endpoint, method, data);
                if (response.meta?.code === 200 || response.meta?.code === 201) {
                    showAlert(response.meta.message || 'Ketentuan berkas berhasil disimpan', 'success');
                    closeModal();
                    this.reset();
                    loadKetentuanBerkas();
                } else {
                    showAlert(response.meta?.message || 'Gagal menyimpan ketentuan berkas', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menyimpan ketentuan berkas', 'error');
            }
        });

        // Reset form saat modal dibuka untuk tambah baru
        function openModal() {
            document.getElementById('berkasModal').classList.remove('hidden');
            document.getElementById('berkasForm').reset();
            document.getElementById('berkasForm').removeAttribute('data-id');
            document.getElementById('modalTitle').textContent = 'Tambah Ketentuan Berkas';
        }

        // Muat data saat halaman dimuat
        document.addEventListener('DOMContentLoaded', loadKetentuanBerkas);
    </script>
@endsection

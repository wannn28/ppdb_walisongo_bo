@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manajemen Berkas Peserta</h1>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Peserta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketentuan
                            Berkas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal
                            Upload</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir
                            Update</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="berkasTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data akan dimasukkan lewat JS -->
                </tbody>
            </table>
            <div class="px-6 py-4">
                <div id="pagination" class="flex justify-center items-center gap-2 mt-4">
                    <!-- Tombol pagination akan di-generate lewat JavaScript -->
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Preview File -->
    <div id="previewModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-3/4 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Preview Berkas</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="h-96 bg-gray-100 rounded-lg flex items-center justify-center">
                <iframe id="filePreview" class="w-full h-full rounded-lg"></iframe>
            </div>
        </div>
    </div>

    <!-- Modal Tolak Berkas -->
    <div id="tolakModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
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
                        <button type="button" onclick="closeTolakModal()"
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
        document.addEventListener('DOMContentLoaded', async () => {
            const response = await AwaitFetchApi('admin/berkas', 'GET');

            if (response && response.data) {
                const tbody = document.getElementById('berkasTableBody');
                tbody.innerHTML = ''; // Kosongkan tbody dulu
                response.data.forEach((berkas, index) => {
                    const peserta = berkas.peserta || {}; // Add null check with default empty object
                    const ketentuan = berkas.ketentuan_berkas;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${peserta.id ? 'PSR' + peserta.id.toString().padStart(3, '0') : 'N/A'}</td>
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
        });
    </script>

    <script>
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
                    loadBerkasPeserta(); // panggil ulang tanpa reload
                } else {
                    showAlert(`Gagal menghapus berkas: ${response.meta?.message}`, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('Terjadi kesalahan saat menghapus berkas', 'error');
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            loadBerkasPeserta(1); // mulai dari halaman 1
        });

        async function loadBerkasPeserta(page = 1) {
            const response = await AwaitFetchApi(`admin/berkas?page=${page}`, 'GET');
            const tbody = document.getElementById('berkasTableBody');
            tbody.innerHTML = ''; // Kosongkan isi tabel

            if (response && response.data) {
                response.data.forEach((berkas, index) => {
                    const peserta = berkas.peserta || {}; // Add null check with default empty object
                    const ketentuan = berkas.ketentuan_berkas;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${peserta.id ? 'PSR' + peserta.id.toString().padStart(3, '0') : 'N/A'}</td>
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

                // Render pagination
                renderPagination(response.pagination);
            }
        }

    function renderPagination(pagination) {
        const container = document.getElementById('pagination');
        container.innerHTML = '';

        const { page, total_pages } = pagination;

        // Tambahkan informasi halaman
        const info = document.createElement('span');
        info.className = 'text-sm text-gray-700';
        info.innerHTML = `Menampilkan halaman <span class="font-medium">${page}</span> dari <span class="font-medium">${total_pages}</span>`;
        container.appendChild(info);

        // Tombol navigasi
        const nav = document.createElement('div');
        nav.className = 'flex gap-2';

        // Tombol Sebelumnya
        const prev = document.createElement('button');
        prev.innerHTML = 'Sebelumnya';
        prev.className = `px-3 py-1 border rounded-md ${page === 1 ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'}`;
        prev.disabled = page === 1;
        prev.onclick = () => page > 1 && loadBerkasPeserta(page - 1);
        nav.appendChild(prev);

        // Tombol Selanjutnya
        const next = document.createElement('button');
        next.innerHTML = 'Selanjutnya';
        next.className = `px-3 py-1 border rounded-md ${page === total_pages ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-gray-100'}`;
        next.disabled = page === total_pages;
        next.onclick = () => page < total_pages && loadBerkasPeserta(page + 1);
        nav.appendChild(next);

        container.appendChild(nav);
    }
    </script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Peringkat Peserta</h1>
        
        <!-- Jurusan Selection -->
        <div class="mb-6">
            <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-2">Pilih Jurusan</label>
            <select id="jurusan" class="w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="">-- Pilih Jurusan --</option>
            </select>
        </div>

        <!-- Ranking Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Peringkat
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Peserta
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Skor
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody id="rankingTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex items-center justify-between">
            <div class="flex-1 flex justify-between sm:hidden">
                <button id="prevPage" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Previous
                </button>
                <button id="nextPage" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span id="currentPage" class="font-medium">1</span> to <span id="totalPages" class="font-medium">1</span> of <span id="totalItems" class="font-medium">0</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button id="firstPage" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">First</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button id="prevPage" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button id="nextPage" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button id="lastPage" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Last</span>
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let selectedJurusan = null;
    let totalPages = 1;
    let totalItems = 0;

    // Fetch all jurusan
    async function fetchJurusan() {
        try {
            const result = await AwaitFetchApi('admin/jurusan', 'GET');
            
            if (result.meta && result.meta.code === 200) {
                const jurusanSelect = document.getElementById('jurusan');
                result.data.forEach(jurusan => {
                    const option = document.createElement('option');
                    option.value = jurusan.id;
                    option.textContent = jurusan.jurusan;
                    jurusanSelect.appendChild(option);
                });
                console.log('Jurusan loaded:', result.data);
            } else if (result.success) {
                const jurusanSelect = document.getElementById('jurusan');
                result.data.forEach(jurusan => {
                    const option = document.createElement('option');
                    option.value = jurusan.id;
                    option.textContent = jurusan.nama || jurusan.jurusan;
                    jurusanSelect.appendChild(option);
                });
                console.log('Jurusan loaded:', result.data);
            } else {
                console.error('Error loading jurusan:', result);
            }
        } catch (error) {
            console.error('Error fetching jurusan:', error);
        }
    }

    // Fetch rankings
    async function fetchRankings(page = 1) {
        if (!selectedJurusan) return;

        const tableBody = document.getElementById('rankingTableBody');
        
        try {
            const result = await AwaitFetchApi(`admin/peringkat?page=${page}&jurusan_id=${selectedJurusan}&sort_by=score&order_by=desc`, 'GET');
            console.log('Ranking result:', result);
            
            if (result.success) {
                tableBody.innerHTML = '';

                result.data.data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.nama_peserta || item.nama || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.score || 0}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.status || '-'}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Update pagination info
                currentPage = result.current_page || result.data.current_page;
                totalPages = result.last_page || result.data.last_page;
                totalItems = result.total || result.data.total;

                document.getElementById('currentPage').textContent = currentPage;
                document.getElementById('totalPages').textContent = totalPages;
                document.getElementById('totalItems').textContent = totalItems;

                // Update pagination buttons
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === totalPages;
                document.getElementById('firstPage').disabled = currentPage === 1;
                document.getElementById('lastPage').disabled = currentPage === totalPages;
            } else if (result.meta && result.meta.code === 200) {
                tableBody.innerHTML = '';

                const data = result.data;
                
                data.data.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.nama_peserta || item.nama || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.score || 0}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.status || '-'}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Update pagination info
                currentPage = data.current_page;
                totalPages = data.last_page;
                totalItems = data.total;

                document.getElementById('currentPage').textContent = currentPage;
                document.getElementById('totalPages').textContent = totalPages;
                document.getElementById('totalItems').textContent = totalItems;

                // Update pagination buttons
                document.getElementById('prevPage').disabled = currentPage === 1;
                document.getElementById('nextPage').disabled = currentPage === totalPages;
                document.getElementById('firstPage').disabled = currentPage === 1;
                document.getElementById('lastPage').disabled = currentPage === totalPages;
            } else if (result.meta && result.meta.code === 400) {
                console.error('Error:', result.meta.message);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            ${result.meta.message || 'Data tidak ditemukan'}
                        </td>
                    </tr>
                `;
            } else {
                // Display error message if needed
                console.error('Error:', result.message);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Data tidak ditemukan
                        </td>
                    </tr>
                `;
            }
        } catch (error) {
            console.error('Error fetching rankings:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        Terjadi kesalahan saat memuat data
                    </td>
                </tr>
            `;
        }
    }

    // Event listeners
    document.getElementById('jurusan').addEventListener('change', function(e) {
        selectedJurusan = e.target.value;
        if (selectedJurusan) {
            currentPage = 1;
            fetchRankings(currentPage);
        }
    });

    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            fetchRankings(currentPage);
        }
    });

    document.getElementById('nextPage').addEventListener('click', function() {
        if (currentPage < totalPages) {
            currentPage++;
            fetchRankings(currentPage);
        }
    });

    document.getElementById('firstPage').addEventListener('click', function() {
        currentPage = 1;
        fetchRankings(currentPage);
    });

    document.getElementById('lastPage').addEventListener('click', function() {
        currentPage = totalPages;
        fetchRankings(currentPage);
    });

    // Initial fetch
    fetchJurusan();
});
</script>
@endsection 
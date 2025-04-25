@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen User</h1>
        <div class="flex gap-4">
            <input type="text" id="searchInput" placeholder="Cari user..." class="border rounded-lg px-4 py-2 w-64">
            {{-- <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center" onclick="openModal()">
                <i class="fas fa-plus mr-2"></i> Tambah User
            </button> --}}
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Telepon</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
        
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="mt-4 flex justify-between items-center bg-white px-4 py-3 sm:px-6 rounded-lg shadow-md">
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-700">
                Menampilkan halaman <span class="font-medium" id="currentPage">1</span> dari 
                <span class="font-medium" id="totalPages">1</span>
            </span>
        </div>
        
        <div class="flex gap-2">
            <button onclick="handlePagination('prev')" class="px-3 py-1 border rounded-md hover:bg-gray-50" id="prevBtn">
                Previous
            </button>
            <button onclick="handlePagination('next')" class="px-3 py-1 border rounded-md hover:bg-gray-50" id="nextBtn">
                Next
            </button>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div id="userModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Tambah User</h3>
            <form id="userForm">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="no_telp">
                        No. Telepon
                    </label>
                    <input type="text" id="no_telp" name="no_telp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="role">
                        Role
                    </label>
                    <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Status
                    </label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="1" class="form-radio" checked>
                            <span class="ml-2">Aktif</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="0" class="form-radio">
                            <span class="ml-2">Tidak Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
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
let allUsers = [];

let currentPage = 1;
let totalPages = 1;
let perPage = 10;

async function fetchData(page = 1, limit = 10) {
    try {
        const response = await AwaitFetchApi(`admin/users?page=${page}&per_page=${limit}`, 'GET');
        if(response?.data) {
            allUsers = response.data;
            renderTable(allUsers);
            updatePagination(response.pagination);
        }
    } catch (error) {
        // showAlert('Gagal memuat data user', 'error');
    }
}

function updatePagination(pagination) {
    currentPage = pagination.page;
    totalPages = pagination.total_pages;
    perPage = pagination.per_page;
    
    document.getElementById('currentPage').textContent = currentPage;
    document.getElementById('totalPages').textContent = totalPages;
    document.getElementById('perPage').value = perPage;
    
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
}

function handlePagination(action) {
    if(action === 'prev' && currentPage > 1) {
        currentPage--;
    } else if(action === 'next' && currentPage < totalPages) {
        currentPage++;
    } else if(typeof action === 'object') {
        // Handle per page change
        perPage = parseInt(document.getElementById('perPage').value);
        currentPage = 1;
    }
    
    fetchData(currentPage, perPage);
}

function renderTable(users) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';
    
    users.forEach((user, index) => {
        const row = `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">${user.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">${user.no_telp}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded ${user.role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                        ${user.role}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${user.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${user.status ? 'Aktif' : 'Tidak Aktif'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${new Date(user.created_at).toLocaleString()}</td>
                <td class="px-6 py-4 whitespace-nowrap">${new Date(user.updated_at).toLocaleString()}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                </td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
}

function searchUser() {
    const searchTerm = document.getElementById('searchInput').value.trim();
    if (!searchTerm) {
        renderTable(allUsers);
        return;
    }

    AwaitFetchApi(`admin/users?search=${searchTerm}`, 'GET')
        .then(response => {
            if (response.data && response.data.length > 0) {
                renderTable(response.data);
            } else {
                showAlert('User tidak ditemukan', 'warning');
                renderTable([]);
            }
        })
        .catch(error => {
            showAlert('Gagal mencari user', 'error');
            console.error('Search error:', error);
        });
}

document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        searchUser();
    }
});

// Panggil fetchData saat halaman dimuat
document.addEventListener('DOMContentLoaded', fetchData);

function openModal() {
    document.getElementById('userModal').classList.remove('hidden');
    document.getElementById('modalTitle').textContent = 'Tambah User';
    document.getElementById('userForm').reset();
}

function closeModal() {
    document.getElementById('userModal').classList.add('hidden');
}

async function saveUser(formData, id = null) {
    try {
        const method = id ? 'PUT' : 'POST';
        const url = id ? `admin/user/${id}` : 'admin/user';
        
        const response = await AwaitFetchApi(url, method, formData);
        
        if(response.meta?.code === 200) {
            showAlert(`User berhasil ${id ? 'diupdate' : 'ditambahkan'}`, 'success');
            await fetchData();
            return true;
        }
        return false;
    } catch (error) {
        showAlert(`Gagal menyimpan user: ${error.message}`, 'error');
        return false;
    }
}
document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = {
        no_telp: document.getElementById('no_telp').value,
        role: document.getElementById('role').value,
        status: document.querySelector('input[name="status"]:checked').value
    };
    
    const userId = this.dataset.editingId || null;
    const success = await saveUser(formData, userId);
    
    if(success) {
        closeModal();
        delete this.dataset.editingId;
    }
});
</script>
@endsection
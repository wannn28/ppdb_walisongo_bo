<nav id="sidebar" class="fixed top-0 left-0 h-screen bg-[#19274d] text-white shadow-lg transition-all duration-300 z-10">
  <div class="flex justify-between items-center p-4 border-b border-white/20">
    <div class="sidebar-header flex items-center">
      <span class="text-green-400 font-bold text-lg">🌿</span>
      <span class="ml-2 font-bold text-lg sidebar-text">WALISONGO</span>
    </div>
    <button id="sidebar-toggle-btn" class="text-white hover:text-green-400">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  <div class="sidebar-content overflow-y-auto h-[calc(100vh-64px)]">
    <ul class="mt-2 space-y-1 text-sm">
      <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('dashboard') ? 'bg-blue-600/30 text-blue-300' : '' }}">
          <span class="nav-icon">📊</span>
          <span class="sidebar-text ml-3">Dashboard</span>
        </a>
      </li>
      
      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text">Media Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('homepage') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('homepage') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">🏠</span>
              <span class="sidebar-text ml-3">Homepage</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('berita') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('berita') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">📰</span>
              <span class="sidebar-text ml-3">Berita</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jadwal') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('jadwal') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">🕒</span>
              <span class="sidebar-text ml-3">Jadwal</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('biaya-reguler') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('biaya-reguler') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">💰</span>
              <span class="sidebar-text ml-3">Biaya Reguler</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text">Berkas Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('ketentuan-berkas') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('ketentuan-berkas') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">📂</span>
              <span class="sidebar-text ml-3">Ketentuan Berkas</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('berkas-peserta') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('berkas-peserta') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">🗃️</span>
              <span class="sidebar-text ml-3">Berkas Peserta</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text">User Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('detail-user') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('detail-user') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">👤</span>
              <span class="sidebar-text ml-3">Detail User</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text">Peserta Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('detail-peserta') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('detail-peserta') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">📋</span>
              <span class="sidebar-text ml-3">Detail Peserta</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text">Transaksi Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('transaksi-user') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('transaksi-user') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">💳</span>
              <span class="sidebar-text ml-3">Transaksi User</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pengaturan-biaya') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('pengaturan-biaya') ? 'bg-white/10' : '' }}">
              <span class="nav-icon">⚙️</span>
              <span class="sidebar-text ml-3">Pengaturan Biaya</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>

    <div class="mt-6 px-4 pb-4">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="text-red-400 hover:text-red-600 font-semibold flex items-center">
          <span class="nav-icon">🚪</span>
          <span class="sidebar-text ml-3">Logout</span>
        </button>
      </form>
    </div>
  </div>
</nav>

<!-- Toggle button for collapsed sidebar -->
<button id="sidebar-expand-btn" class="fixed top-4 left-6 bg-[#19274d] text-white p-2  shadow-lg z-20 hidden">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
  </svg>
</button>

<!-- Main content wrapper -->
<div id="content" class="transition-all duration-300 ml-64">
  <!-- Your main content goes here -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const expandBtn = document.getElementById('sidebar-expand-btn');
  const sidebarTexts = document.querySelectorAll('.sidebar-text');
  
  // Function to collapse sidebar
  function collapseSidebar() {
    sidebar.classList.remove('w-64');
    sidebar.classList.add('w-16');
    content.classList.remove('ml-64');
    content.classList.add('ml-16');
    expandBtn.classList.remove('hidden');
    sidebarTexts.forEach(text => text.classList.add('hidden'));
  }
  
  // Function to expand sidebar
  function expandSidebar() {
    sidebar.classList.remove('w-16');
    sidebar.classList.add('w-64');
    content.classList.remove('ml-16');
    content.classList.add('ml-64');
    expandBtn.classList.add('hidden');
    sidebarTexts.forEach(text => text.classList.remove('hidden'));
  }
  
  // Initial setup
  sidebar.classList.add('w-64');
  
  // Toggle sidebar on button click
  toggleBtn.addEventListener('click', function() {
    if (sidebar.classList.contains('w-64')) {
      collapseSidebar();
    } else {
      expandSidebar();
    }
  });
  
  // Expand sidebar when expand button is clicked
  expandBtn.addEventListener('click', expandSidebar);
  
  // Collapse sidebar on small screens by default
  function handleResize() {
    if (window.innerWidth < 768) {
      collapseSidebar();
    } else {
      expandSidebar();
    }
  }
  
  // Initial check and add resize listener
  handleResize();
  window.addEventListener('resize', handleResize);
});
</script>
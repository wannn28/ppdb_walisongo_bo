<nav id="sidebar" class="fixed top-0 left-0 h-screen bg-[#19274d] text-white shadow-lg transition-all duration-300 z-10">
  <div class="flex justify-between items-center p-4 border-b border-white/20">
    <div class="sidebar-header flex items-center justify-center">
      <span class="text-green-400 font-bold text-lg">🌿</span>
      <span class="ml-2 font-bold text-lg sidebar-text transition-opacity duration-300">WALISONGO</span>
    </div>
    <button id="sidebar-toggle-btn" class="text-white hover:text-green-400 transition-transform duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
      </svg>
    </button>
  </div>

  <div class="sidebar-content overflow-y-auto h-[calc(100vh-64px)]">
    <ul class="mt-2 space-y-1 text-sm">
      <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('dashboard') ? 'bg-blue-600/30 text-blue-300' : '' }}">
          <span class="nav-icon flex items-center justify-center min-w-[20px]">📊</span>
          <span class="sidebar-text ml-3 transition-opacity duration-300">Dashboard</span>
        </a>
      </li>
      
      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text transition-opacity duration-300">Media Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('homepage') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('homepage') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">🏠</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Homepage</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('berita') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('berita') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">📰</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Berita</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jadwal') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('jadwal') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">🕒</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Jadwal</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text transition-opacity duration-300">Berkas Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('ketentuan-berkas') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('ketentuan-berkas') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">📂</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Ketentuan Berkas</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('berkas-peserta') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('berkas-peserta') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">🗃️</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Berkas Peserta</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text transition-opacity duration-300">User Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('detail-user') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('detail-user') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">👤</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Detail User</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pesan') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('pesan') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">✉️</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Pesan</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text transition-opacity duration-300">Peserta Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('detail-peserta') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('detail-peserta') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">📋</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Detail Peserta</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pekerjaan-ortu') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('pekerjaan-ortu') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">👨‍💼</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Pekerjaan Ortu</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('biodata-ortu') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('biodata-ortu') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">👪</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Biodata Ortu</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('jurusan') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('jurusan') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">🎓</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Kelas</span>
            </a>
          </li>
        </ul>
      </li>

      <li class="nav-section mt-4">
        <div class="px-4 text-gray-400 uppercase text-xs sidebar-text transition-opacity duration-300">Transaksi Management</div>
        <ul class="mt-1">
          <li class="nav-item">
            <a href="{{ route('transaksi-user') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('transaksi-user') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">💳</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Transaksi User</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('pengaturan-biaya') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('pengaturan-biaya') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">⚙️</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Pengaturan Biaya</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('biaya') }}" class="flex items-center px-4 py-2 hover:bg-white/10 rounded mx-2 {{ request()->routeIs('biaya') ? 'bg-white/10' : '' }}">
              <span class="nav-icon flex items-center justify-center min-w-[20px]">💰</span>
              <span class="sidebar-text ml-3 transition-opacity duration-300">Biaya</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>

    <div class="mt-6 px-4 pb-4">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="text-red-400 hover:text-red-600 font-semibold flex items-center">
          <span class="nav-icon flex items-center justify-center min-w-[20px]">🚪</span>
          <span class="sidebar-text ml-3 transition-opacity duration-300">Logout</span>
        </button>
      </form>
    </div>
  </div>
</nav>

<!-- Toggle button for collapsed sidebar -->
<button id="sidebar-expand-btn" class="fixed top-4 left-4 bg-[#19274d] text-white p-2 rounded-full shadow-lg z-20 hidden transition-all duration-300">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
  </svg>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sidebar = document.getElementById('sidebar');
  const mainContent = document.getElementById('main-content');
  const toggleBtn = document.getElementById('sidebar-toggle-btn');
  const expandBtn = document.getElementById('sidebar-expand-btn');
  const sidebarTexts = document.querySelectorAll('.sidebar-text');
  const sidebarHeader = document.querySelector('.sidebar-header');
  
  // Function to save sidebar state to localStorage
  function saveSidebarState(isExpanded) {
    localStorage.setItem('sidebarExpanded', isExpanded ? 'true' : 'false');
  }
  
  // Function to collapse sidebar
  function collapseSidebar() {
    sidebar.style.width = '64px';
    mainContent.style.marginLeft = '64px';
    expandBtn.classList.remove('hidden');
    toggleBtn.classList.add('hidden'); // Hide toggle button when collapsed
    sidebarTexts.forEach(text => {
      text.classList.add('opacity-0');
      setTimeout(() => {
        text.classList.add('hidden');
      }, 200);
    });
    sidebarHeader.classList.add('justify-center');
    saveSidebarState(false);
  }
  
  // Function to expand sidebar
  function expandSidebar() {
    sidebar.style.width = '256px';
    mainContent.style.marginLeft = '256px';
    expandBtn.classList.add('hidden');
    toggleBtn.classList.remove('hidden'); // Show toggle button when expanded
    sidebarHeader.classList.remove('justify-center');
    
    sidebarTexts.forEach(text => {
      text.classList.remove('hidden');
      setTimeout(() => {
        text.classList.remove('opacity-0');
      }, 50);
    });
    saveSidebarState(true);
  }
  
  // Check localStorage for saved state
  const savedState = localStorage.getItem('sidebarExpanded');
  
  // Initial setup based on saved state or default
  if (savedState === 'false') {
    // Initialize in collapsed state
    sidebar.style.width = '64px';
    mainContent.style.marginLeft = '64px';
    toggleBtn.classList.add('hidden');
    expandBtn.classList.remove('hidden');
    sidebarTexts.forEach(text => {
      text.classList.add('opacity-0');
      text.classList.add('hidden');
    });
    sidebarHeader.classList.add('justify-center');
  } else {
    // Initialize in expanded state (default)
    sidebar.style.width = '256px';
    mainContent.style.marginLeft = '256px';
    expandBtn.classList.add('hidden');
  }
  
  // Toggle sidebar on button click
  toggleBtn.addEventListener('click', function() {
    if (sidebar.style.width === '256px') {
      collapseSidebar();
    } else {
      expandSidebar();
    }
  });
  
  // Expand sidebar when expand button is clicked
  expandBtn.addEventListener('click', expandSidebar);
  
  // Collapse sidebar on small screens by default, but respect saved preference
  function handleResize() {
    if (window.innerWidth < 768 && savedState !== 'true') {
      collapseSidebar();
    } else if (window.innerWidth >= 768 && savedState !== 'false') {
      expandSidebar();
    }
  }
  
  // Initial check and add resize listener
  handleResize();
  window.addEventListener('resize', handleResize);
});
</script>
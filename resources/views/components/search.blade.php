@props(['placeholder' => 'Cari...', 'searchFunction' => 'search', 'additionalClasses' => ''])

<div class="p-4 bg-white sticky top-0 z-10 shadow-sm flex justify-center {{ $additionalClasses }}">
    <div class="relative w-full max-w-md">
        <input type="text" 
               id="searchInput"
               placeholder="{{ $placeholder }}" 
               class="w-full h-10 pl-3 pr-10 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
               @keypress="function(e) { if (e.key === 'Enter') { {{ $searchFunction }}(); } }">
        <button onclick="{{ $searchFunction }}()" class="absolute right-2 top-2 text-gray-500 hover:text-blue-500">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>
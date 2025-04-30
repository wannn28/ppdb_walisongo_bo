@props(['id' => 'pagination', 'loadFunction' => 'loadData'])

<div id="{{ $id }}" class="flex justify-between m-4">
    <div class="flex items-center gap-2">
        <span class="text-sm text-gray-700">
            Menampilkan 
            <span id="pagination-start" class="font-medium">0</span>
            sampai
            <span id="pagination-end" class="font-medium">0</span>
            dari
            <span id="pagination-total" class="font-medium">0</span>
            data
        </span>
    </div>
    
    <div class="flex gap-2">
        <button id="prev-page" 
                class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:bg-gray-100 disabled:cursor-not-allowed"
                onclick="{{ $loadFunction }}(currentPage - 1)">
            Sebelumnya
        </button>
        
        <div id="page-numbers" class="flex gap-1"></div>
        
        <button id="next-page" 
                class="px-3 py-1 border rounded-md hover:bg-gray-100 disabled:bg-gray-100 disabled:cursor-not-allowed"
                onclick="{{ $loadFunction }}(currentPage + 1)">
            Selanjutnya
        </button>
    </div>
</div> 
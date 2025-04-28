@props(['resetFunction' => 'resetFilters', 'applyFunction' => null, 'additionalClasses' => ''])

<div class="bg-white p-4 rounded-lg shadow {{ $additionalClasses }}">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{ $slot }}
    </div>
    
    <div class="mt-4 flex justify-end">
        <button id="resetFilters" 
                onclick="{{ $resetFunction }}()" 
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 mr-2">
            Reset Filter
        </button>
        
        @if($applyFunction)
        <button id="applyFilters" 
                onclick="{{ $applyFunction }}()" 
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
            Terapkan Filter
        </button>
        @endif
    </div>
</div> 
@props(['column', 'label', 'sortFunction' => 'handleSort'])

<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer" 
    onclick="{{ $sortFunction }}('{{ $column }}')">
    {{ $label }} <span id="sort-{{ $column }}"></span>
</th> 
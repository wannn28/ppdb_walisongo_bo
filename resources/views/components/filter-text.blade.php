@props(['id' => 'searchInput', 'label' => 'Search', 'placeholder' => 'Cari...', 'onChangeFunction' => null])

<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <input type="text" 
           id="{{ $id }}" 
           placeholder="{{ $placeholder }}" 
           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
           @if($onChangeFunction) oninput="{{ $onChangeFunction }}(this.value)" @endif>
</div> 
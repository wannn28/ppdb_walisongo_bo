@props(['id', 'label', 'options' => [], 'onChangeFunction' => null])

<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select id="{{ $id }}" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            @if($onChangeFunction) onchange="{{ $onChangeFunction }}(this.value)" @endif>
        @foreach($options as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
</div> 
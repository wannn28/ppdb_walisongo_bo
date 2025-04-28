@props(['startId' => 'startDate', 'endId' => 'endDate', 'label' => 'Date Range', 'onStartChangeFunction' => null, 'onEndChangeFunction' => null])

<div>
    <label class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <div class="flex gap-2 mt-1">
        <input type="date" 
               id="{{ $startId }}" 
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
               @if($onStartChangeFunction) onchange="{{ $onStartChangeFunction }}(this.value)" @endif>
        <input type="date" 
               id="{{ $endId }}" 
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
               @if($onEndChangeFunction) onchange="{{ $onEndChangeFunction }}(this.value)" @endif>
    </div>
</div> 
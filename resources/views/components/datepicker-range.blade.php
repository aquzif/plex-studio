@props([
    'error' => null
])

<div
    x-data="{ value: @entangle($attributes->wire('model')).live, label: '' }"
{{--    x-on:change="console.log($event.target.value);value = $event.target.value"--}}
    x-init="flatpickr($refs.input
                , {mode: 'range'
                , locale: '{{Lang::locale()}}'
                , dateFormat: 'Y-m-d'
                , onChange: (selectedDates, dateStr, instance) => {
                    value = JSON.stringify(selectedDates.map(date => DateUtils.formatDate(date)));
                    label = dateStr;
                }
    })"
>
    <input
        {{ $attributes->whereDoesntStartWith('wire:model') }}
        x-ref="input"
        x-bind:value="label"
        type="text"
        class=" block w-full shadow-sm sm:text-lg bg-gray-50 border-gray-300 @if($error) focus:ring-danger-500 focus:border-danger-500 border-danger-500 text-danger-500     @else focus:ring-primary-500 focus:border-primary-500 @endif rounded-md"
    />
</div>

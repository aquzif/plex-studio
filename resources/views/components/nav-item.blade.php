

@php
    $classes = ($active ?? false)
                ? 'bg-gray-800'
                : '';
    $classes.= ' w-full p-2 hover:bg-gray-800 rounded-md flex flex-row cursor-pointer mb-1
        items-center

    ';
@endphp

<a wire:navigate {{$attributes->merge(['class' => $classes])}}>
    <x-dynamic-component :component="$icon" class="w-6 h-6 text-white" />
    <p class="{{$active ? 'text-white' : 'text-gray-300'}} ml-3 text-sm py-1 font-semibold" >{{$name}}</p>
</a>

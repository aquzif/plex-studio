@php

    //throw error if the required attributes are not passed
    if (!isset($attributes['name'])) {
        throw new Exception('The name attribute is required');
    }

    $color = 'primary';
    $error = $errors->has($name);
    $darkMode = isset($dark) && $dark == 'true';

    $classes = "mt-1 block w-full px-3 py-2 border
                  rounded-md shadow-sm
                   sm:text-sm
                   bg-white text-gray-500 focus:ring-primary-500
                   dark:bg-gray-700 dark:text-gray-100 dark:focus:ring-primary-500
                   ";


    if ($error) {
        $classes .= ' border-red-500 focus:border-red-500 dark:border-red-500';
    } else {
        $classes .= ' border-gray-300 focus:border-primary-500 dark:border-gray-700';
    }

    $label = $label ?? '';
    $name = $name ?? '';


@endphp


<div>
    <label for="{{$name}}"
           @if($label === '') hidden @endif
           class="block text-sm font-semibold

        {{$error ? 'text-red-500 dark:text-red-500' : 'text-gray-700 dark:text-gray-100' }}">{{$label}}</label>
    <input
        {{$attributes->merge(['class' => $classes])}}
    />
    @error($name)
        <p class="mt-1 block text-sm font-medium text-red-500">{{$message}}</p>
    @enderror
</div>

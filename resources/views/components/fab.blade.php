<?php


$classes = "rounded-full fixed
    bottom-4 right-4
    bg-primary-500 text-white
    p-3 shadow-lg
    cursor-pointer
    hover:bg-primary-600
    transition-all duration-300
    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500
    z-50
";


?>

<button
    {{$attributes->merge([
        'class' => $classes
    ])}}
>
    <x-heroicon-m-plus class="w-8 h-8" />
</button>


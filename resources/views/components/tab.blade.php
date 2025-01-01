<?php

$active = $active ?? false;

//make it like in mui
$classes = '
    px-5 py-2
    mt-4
    border-b-2
    cursor-pointer
    dark:hover:bg-gray-800
    dark:text-white
    hover:g-gray-200
    uppercase

';

if ($active) {
    $classes .= ' border-primary-500';
}else {
    $classes .= ' border-transparent';
}

?>

<div
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{$slot}}
</div>

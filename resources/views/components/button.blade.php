<?php

$color = $color ?? 'primary';

$classes = "flex justify-center py-2 px-4 border border-transparent rounded-md
           shadow-sm text-sm font-medium text-white bg-$color-500 hover:bg-$color-600
           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-$color-500
           disabled:opacity-50 disabled:cursor-not-allowed
           ";



?>
<button
    {{$attributes->merge([
        'class' => $classes
    ])}}
    >
        {{$slot}}
</button>

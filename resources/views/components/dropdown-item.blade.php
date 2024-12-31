<div
    {{ $attributes->merge([
        'class' => 'block font-normal px-4 py-2 text-md text-gray-500 hover:bg-gray-100 w-full cursor-pointer
            dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-white
            dark:bg-neutral-700
        '])
    }}
>
    {{$slot}}
</div>

<button
    {{ $attributes->merge([
        'class' => 'flex flex-col items-center justify-center w-8 h-8 rounded-full hover:bg-[#00000020] cursor-pointer text-white
        transition-colors duration-300 ease-in-out
        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50
        '

        ]) }}
>
    {{$slot}}
</button>

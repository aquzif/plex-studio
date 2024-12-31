<div class="absolute" x-data="{ open: false }">
    <div @mouseenter="open = true" @mouseleave="open = false" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open" @mouseenter="open = true" @mouseleave="open = false"
         class="absolute z-10 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg">
        {{ $slot }}
    </div>
</div>

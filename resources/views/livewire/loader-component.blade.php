<div>
    <div
        class="absolute w-full h-full"
        x-show="$store.loader.isLoading"
        x-on:show-loader.window="$store.loader.show()"
        x-on:hide-loader.window="$store.loader.hide()"
        style="z-index: 999999999;background-color: rgba(0,0,0,0.4)"
    >
        <div class="flex items-center justify-center h-full">
            <div class="loader ease-linear rounded-full border-8 border-t-8 border-gray-200 h-32 w-32"></div>
        </div>

    </div>
    {{$slot ?? ''}}
</div>

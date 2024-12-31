<?php

    $showTitle = $showTitle ?? true;
    $showButtons = $showButtons ?? true;

?>

<div x-data="{open: @entangle('open').live}" >
    <div x-show="open"  >

       <div @click.self="open = false"
            class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center"
            style="background: rgba(0, 0, 0, 0.25);"
       >
            <article class="m-auto bg-white rounded overflow-hidden flex flex-col md:min-w-32 min-w-[90%]
                    dark:bg-gray-800 dark:text-gray-200
                "
                style="max-height: 90vh; max-width: 90vw;box-shadow: 0 15px 30px 0 rgba(0, 0, 0, 0.25);"
            >
                <header class="py-4 px-8 flex items-center justify-between w-full"
                    x-bind:style="darkMode? 'border-bottom: 1px solid #555' : 'border-bottom: 1px solid #ddd'"
                        x-show="{{$showTitle}}"
                >
                    <div
                        class="flex items-center gap-2 line-clamp-1 font-bold text-sm"
                    >
                        {{$title ?? 'Title'}}
                    </div>
                    <button class="p-0 m-0 w-10 h-10 flex items-center justify-center cursor-pointer" @click="open = false" >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                            <path fill="none" d="M0 0h24v24H0z" />
                            <path fill="currentColor" d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" />
                        </svg>
                    </button>
                </header>
                <section class="px-8 py-6 overflow-auto" >
                    {{$slot ?? 'Content'}}
                </section>
                <footer
                    class="px-8 py-5 flex items-center justify-end w-full gap-3 relative"
                    x-bind:style="darkMode? 'border-top: 1px solid #555' : 'border-top: 1px solid #ddd'"
                    x-show="{{$showButtons}}"
                >
                    @if(isset($buttons))
                        {{$buttons}}
                    @else
                        <x-button @click="open = false" >Zamknij</x-button>
                    @endif
                </footer>
            </article>
       </div>

    </div>
</div>

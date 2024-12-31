@php
    $color = 'indigo';
    $error = $errors->has($name);
    $darkMode = $dark ?? false;





    $selectClasses = "mt-1 block w-full px-3 py-2 border
                    rounded-md shadow-sm focus:ring-indigo-500
                    flex flex-row justify-between text-left
                     sm:text-sm";
    $whiteClassesSelect = " bg-white text-gray-500";
    $darkClassesSelect = " bg-gray-800 text-gray-100";

    $OptionsContainerClasses = "rounded-md ring-1 ring-black ring-opacity-5 py-1";
    $whiteClassesOptionsContainer = " bg-white";
    $darkClassesOptionsContainer = " bg-gray-800";


    $OptionClasses = "block w-full px-4 py-2 text-start text-sm leading-5
                            focus:outline-none
                            transition duration-150 ease-in-out";
    $whiteClassesOption = "text-gray-700 hover:bg-gray-100";
    $darkClassesOption = "text-gray-100 hover:bg-gray-700";


    if ($error) {
        $whiteClassesSelect .= ' border-red-500 focus:border-red-500';
    } else {
        $whiteClassesSelect .= ' border-gray-300 focus:border-indigo-500';
    }

    if ($error) {
        $darkClassesSelect .= ' border-red-500 focus:border-red-500';
    } else {
        $darkClassesSelect .= ' border-gray-700 focus:border-indigo-500';
    }

    $selectClasses = preg_replace('/\s+/', ' ', $selectClasses);
    $OptionsContainerClasses = preg_replace('/\s+/', ' ', $OptionsContainerClasses);
    $OptionClasses = preg_replace('/\s+/', ' ', $OptionClasses);
    $darkClassesSelect = preg_replace('/\s+/', ' ', $darkClassesSelect);
    $whiteClassesSelect = preg_replace('/\s+/', ' ', $whiteClassesSelect);
    $darkClassesOptionsContainer = preg_replace('/\s+/', ' ', $darkClassesOptionsContainer);
    $whiteClassesOptionsContainer = preg_replace('/\s+/', ' ', $whiteClassesOptionsContainer);
    $darkClassesOption = preg_replace('/\s+/', ' ', $darkClassesOption);
    $whiteClassesOption = preg_replace('/\s+/', ' ', $whiteClassesOption);


@endphp


<div x-data="{  open: false,selected: $wire.entangle('{{$model}}'),searchVal: ''}" @click.away="open = false" >

    <label for="{{$name}}" class=" block text-sm font-semibold {{$error ? 'text-red-500' : 'text-gray-700'
 }}">
        {{$label ?? ''}}
    </label>
    <button
        @click.prevent="open = !open;
            if(open && '{{$search ?? false}}'){
                $nextTick(() => {
                    $refs.searchInput.focus();
                });
            }
        "

        {{$attributes->except(['wire:model','id','name','options'])}}
        class="{{$selectClasses}}"
        :class="(darkMode || {{$darkMode?'true':'false'}}) && '{{$darkClassesSelect}}' || '{{$whiteClassesSelect}}'"

    >
        <div x-show="!selected" >Wybierz z listy</div>
        @foreach($options as $v)
                <div x-show="selected == '{{$v['value']}}'" >{{$v['label']}}</div>
        @endforeach
        <div>
            <x-heroicon-c-chevron-down class="h-5 w-5 text-gray-400"/>
        </div>
    </button>
    <div

        x-show="open"
        class="relative"

    >
        <div class="absolute z-50 mt-2 w-full rounded-md shadow-lg end-0">
            <div class="{{$OptionsContainerClasses}}"
                :class="(darkMode || {{$darkMode?'true':'false'}}) && '{{$darkClassesOptionsContainer}}' || '{{$whiteClassesOptionsContainer}}'">
                @if($search ?? false)
                    <div class="mx-2 mb-2" >
                        <x-input
                            type="text"
                            name="search"
                            dark="{{$darkMode?'true':'false'}}"
                            placeholder="Wyszukaj"
                            x-model="searchVal"
                            x-ref="searchInput" />
                    </div>
                @endif
                @foreach($options as $v)
                    <button
                        @click.prevent="open = false;selected = '{{$v['value']}}'{{isset($onChange) ? ";\$wire.$onChange('".json_encode($v)."')" : ''}}"
                        class="{{$OptionClasses}}"
                        x-show="'{{$v['label']}}'.toLowerCase().includes(searchVal.toLowerCase())"
                        :class="(darkMode || {{$darkMode?'true':'false'}}) && '{{$darkClassesOption}}' || '{{$whiteClassesOption}}'"
                    >
                        {{$v['label']}}
                    </button>
                @endforeach
            </div>
        </div>

    </div>
    @error($name)
        <p class="mt-1 block text-sm font-medium text-red-500">{{$message}}</p>
    @enderror
</div>

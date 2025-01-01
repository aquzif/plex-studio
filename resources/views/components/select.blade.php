@php
    $color = 'indigo';
    $error = $errors->has($name);
    $darkMode = $dark ?? false;
    $labelAsHTML = $labelAsHTML ?? false;
    $multiple = $multiple ?? false;





    $selectClasses = "mt-1 block w-full px-3 py-2 border
                    rounded-md shadow-sm focus:ring-indigo-500
                    flex flex-row  text-left justify-between
                     sm:text-sm";


    $whiteClassesSelect = " bg-white text-gray-500";
    $darkClassesSelect = " dark:bg-gray-800 dark:text-gray-100";

    $OptionsContainerClasses = "rounded-md ring-1 ring-black ring-opacity-5 py-1";
    $whiteClassesOptionsContainer = " bg-white";
    $darkClassesOptionsContainer = " dark:bg-gray-800";


    $OptionClasses = "block w-full px-4 py-2 text-start text-sm leading-5
                            focus:outline-none
                            transition duration-150 ease-in-out";
    $whiteClassesOption = "text-gray-700 hover:bg-gray-100";
    $darkClassesOption = "dark:text-gray-100 dark:hover:bg-gray-700";


    if ($error) {
        $whiteClassesSelect .= ' border-red-500 focus:border-red-500';
    } else {
        $whiteClassesSelect .= ' border-gray-300 focus:border-indigo-500';
    }

    if ($error) {
        $darkClassesSelect .= ' dark:border-red-500 dark:focus:border-red-500';
    } else {
        $darkClassesSelect .= ' dark:border-gray-700 dark:focus:border-indigo-500';
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

    $darkClassesOption = $darkClassesOption.' '.$OptionClasses;
    $whiteClassesOption = $whiteClassesOption.' '.$OptionClasses;

    $valueSelected = 'bg-gray-100 text-white dark:bg-gray-700 dark:text-white';

@endphp


<div x-data="{  open: false,selected: $wire.entangle('{{$model}}').live,searchVal: ''}" @click.away="open = false"
>

    <label for="{{$name}}" class=" block text-sm font-semibold
        dark:text-gray-100
     {{$error ? 'text-red-500' : 'text-gray-700'}}">
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
        <div class="flex flex-row flex-wrap gap-2" >
            <div x-show="!selected" >Wybierz z listy</div>

            @foreach($options as $v)
                @if($multiple)
                    <div
                        x-show="JSON.parse(selected).filter((elem)=>elem == '{{$v['value']}}').length > 0"
                        class="border border-gray-300 dark:border-gray-600  pl-2 pr-1 py-0.5 rounded-full flex flex-row"
                    >
                        @if($labelAsHTML)
                            {!! $v['label'] !!}
                            <x-heroicon-s-x-circle class="h-4 w-4 m-0.5 text-gray-500"
                                x-on:click.prevent="
                                    selected = JSON.stringify(JSON.parse(selected).filter((elem)=>elem != '{{$v['value']}}'));open=!open
                                    "
                            />
                        @else
                            <span>{{$v['label']}}</span>
                            <x-heroicon-s-x-circle class="h-4 w-4 m-0.5 text-gray-500"
                                                   x-on:click.prevent="
                                    selected = JSON.stringify(JSON.parse(selected).filter((elem)=>elem != '{{$v['value']}}'));open=!open
                                    "
                            />
                        @endif
                    </div>
                @else
                    <div x-show="selected == '{{$v['value']}}'" >
                        @if($labelAsHTML)
                            {!! $v['label'] !!}
                        @else
                            {{$v['label']}}
                        @endif
                    </div>
                @endif

                @endforeach
        </div>

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
                @if($multiple)
                    <button
                        @click.prevent="
                            selected = JSON.parse(selected).includes('{{$v['value']}}') ? JSON.stringify(JSON.parse(selected).filter((elem)=>elem != '{{$v['value']}}')) : JSON.stringify([...JSON.parse(selected),'{{$v['value']}}']);
                        "
                        x-show="'{{$v['label']}}'.toLowerCase().includes(searchVal.toLowerCase())"
                        x-bind:class="
                            (JSON.parse(selected).filter((elem)=>elem == '{{$v['value']}}').length > 0 ? '{{$valueSelected}} ' : '') +
                            ((darkMode || {{$darkMode?'true':'false'}})
                            && '{{$darkClassesOption}}' || '{{$whiteClassesOption}}')
                        "
                    >
                @else
                    <button
                        @click.prevent="
                            selected = '{{$v['value']}}';
                            open = false;
                        "
                        x-show="'{{$v['label']}}'.toLowerCase().includes(searchVal.toLowerCase())"
                        x-bind:class="
                            (selected == '{{$v['value']}}' ? '{{$valueSelected}} ' : '') +
                            ((darkMode || {{$darkMode?'true':'false'}})
                            && '{{$darkClassesOption}}' || '{{$whiteClassesOption}}')

                        "
                    >
                @endif

                        @if($labelAsHTML)
                            {!! $v['label'] !!}
                        @else
                            {{$v['label']}}
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

    </div>
    @error($name)
        <p class="mt-1 block text-sm font-medium text-red-500">{{$message}}</p>
    @enderror
</div>

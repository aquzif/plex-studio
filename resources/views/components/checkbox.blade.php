<div
    {{$attributes->only('class')->merge([
        'class' => "flex items center"
    ])}}
])}}
>
    <input type="checkbox" id="{{$name ?? ''}}" {{$attributes->except('class')}}
        wire:model="{{$model ?? ''}}"
           class="h-5 w-5 text-primary-600 focus:ring-primary-500 border-gray-300 rounded
            dark:text-gray-700 dark:focus:ring-primary-500 dark:border-gray-700 dark:bg-gray-800"
    >
    <label for="{{$name ?? ''}}" class="ml-2 block
                    text-sm text-gray-900
                    dark:text-gray-100
                    ">{{$label ?? ''}}</label>
</div>

<div

>
    <div class="relative pt-1">
        <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-600">
            <div style="width:{{$max === 0 || $value === 0 ? 0 : $value/$max*100 }}%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center
             @if($error)
                bg-red-500
             @elseif($value/$max < 0.3)
                bg-red-500
            @elseif($value/$max < 0.6)
                bg-yellow-500
            @else
                bg-green-500
             @endif
            "></div>
        </div>
    </div>
</div>

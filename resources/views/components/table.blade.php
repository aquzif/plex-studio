<?php
$dense = $dense ?? 'false';
?>
<table
    class=" max-w-full  divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-700"
    x-data="{tableDense: {{$dense}}}"
>
    <thead class="bg-gray-50 dark:bg-gray-800 ">
        <tr>
            {{ $columns ?? '' }}
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
        {{ $rows ?? ''  }}

    </tbody>
</table>

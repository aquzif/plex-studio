<td :class="
    'whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 {{$class ?? ''}}'
    + (tableDense ? ' py-1 px-2' : ' px-6 py-4')
  "
  {{$attributes->except('class')}}}

>
    {{ $slot }}
</td>

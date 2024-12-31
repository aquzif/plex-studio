<div {{$attributes->merge([
    'class' => 'p-4 sm:p-8 bg-white shadow sm:rounded-lg dark:bg-gray-800 dark:shadow-none dark:border dark:border-gray-700'
])}} >
    {{$slot}}
</div>

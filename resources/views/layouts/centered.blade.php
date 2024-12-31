
@extends('layouts.main')

@section('body')
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100
        dark:bg-gray-700
    ">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md sm:rounded-lg
            dark:bg-gray-800 dark:shadow-none dark:border dark:border-gray-700
        ">
            {{$slot}}
        </div>
    </div>

@endsection

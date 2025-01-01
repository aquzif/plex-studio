
@extends('layouts.main')



@section('body')
    {{--<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
         x-cloak
         x-data="{darkMode: localStorage.getItem('dark') === 'true'}"
         x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
         x-bind:class="{'dark': darkMode}"
    >
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md sm:rounded-lg">
            <button
                @click="darkMode = !darkMode"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                toggle
            </button>
            <x-select name="selectedLedger" :options="[1,2,3]"  />
        </div>
    </div>--}}

{{--    {{Lang::locale()}}<br/>--}}
{{--    {{App::currentLocale()}}--}}

    <button
        @click="window.Toaster.sendSuccess()"
    >
        toast
    </button>
    <x-icon-button >
        <x-heroicon-m-plus />
    </x-icon-button>

@endsection


@extends('layouts.main')

@section('body')
    <div x-data="{ navOpen: false, searchValue: '' }" >

{{--        <livewire:components.navigation />--}}
        <div
{{--            class="lg:ml-80" --}}
        >
            <x-topbar />
            {{$slot}}
        </div>


    </div>

@endsection

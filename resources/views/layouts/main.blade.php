<html
    x-cloak
    x-data="{darkMode: localStorage.getItem('dark') === 'true'}"
    x-init="$watch('darkMode', val => localStorage.setItem('dark', val))"
    x-bind:class="{'dark': darkMode}"
    lang="{{Lang::locale()}}"
>
<head>
    <tallstackui:script />
    @livewireStyles
    <meta charset="UTF-8">
    <link rel="icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Plex Studio</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" type="text/css" href="tooltipster/dist/css/tooltipster.bundle.min.css" />
    @vite('resources/css/app.css')


</head>
<body class="bg-gray-100
    dark:bg-neutral-700
">

    <livewire:toaster-component />
    @yield('body')
    @livewireScripts
    @vite('resources/js/app.js')
</body>

</html>


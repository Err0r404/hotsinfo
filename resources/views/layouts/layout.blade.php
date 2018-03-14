<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    {{-- BS4 meta tags --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- Laravel meta tags --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Laravel CSS that includes BS4 --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')

    <title>{{ config('app.name', 'HOTS Info') }}</title>
</head>
<body>
@include('layouts.navbar')

<div class="container">
    @yield('container')
</div>

{{-- Laravel JS that includes BS4 --}}
<script src="{{ asset('js/app.js') }}"></script>
@yield('script')
</body>
</html>
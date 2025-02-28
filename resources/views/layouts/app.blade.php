<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'PalladiumHub') }}</title> --}}
    <title>@yield('admin-title')</title>
    <!-- Fonts -->

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/admin/theme/img/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/font-awesome.min.css') }}">

    <!-- Feathericon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/feathericon.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/admin/theme/plugins/morris/morris.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/css/style.css') }} ">


</head>

<body>
    <x-toast />
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

<!-- jQuery -->
<script src="{{ asset('assets/admin/theme/js/jquery-3.2.1.min.js') }}"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/admin/theme/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/js/bootstrap.min.js') }}"></script>

<!-- Slimscroll JS -->
<script src="{{ asset('assets/admin/theme/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('assets/admin/theme/plugins/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('assets/admin/theme/js/chart.morris.js') }}"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/admin/theme/js/script.js') }}"></script>


</html>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('app.name', 'TROPICAL GIDA') }}</title>
    <meta name="description" content="This is the inventory management system for Tropical Abroad Export Company.">
    <meta name="author" content="Yuyuan Zhang">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{asset('master/media/favicons/favicon.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('master/media/favicons/favicon-192x192.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('master/media/favicons/apple-touch-icon-180x180.png')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700">
    @yield('style')
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <link rel="stylesheet" href="{{asset('master/css/custom.css')}}">
    
</head>

<body>
    <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed page-header-dark main-content-narrow">
        @include('layouts.aside')
        @include('layouts.header')
        <main id="main-container">

            @yield('content')
            
        </main>
    </div>
    <script src="{{asset('master/js/dashmix.core.min-2.0.js')}}"></script>
    <script src="{{asset('master/js/dashmix.app.min-2.0.js')}}"></script>
    @yield('script')
</body>

</html>
<!doctype html>
<html lang="{{ app()->getLocale() }}">

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
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
</head>

<body>
    <div id="page-container">
        <main id="main-container">
            <div class="bg-image" style="background-image: url('master/media/photos/bg_auth.jpg');">
                <div class="row no-gutters justify-content-center">
                    <div class="hero-static col-sm-8 col-md-6 col-xl-4 d-flex align-items-center p-2 px-sm-0">
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{asset('master/js/dashmix.core.min-2.0.js')}}"></script>
    <script src="{{asset('master/js/dashmix.app.min-2.0.js')}}"></script>
    <script src="{{asset('master/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('master/js/pages/op_auth_signin.min.js')}}"></script>
</body>

</html>
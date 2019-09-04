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
    
    @yield('style')
    
    <link rel="stylesheet" id="css-main" href="{{asset('master/css/dashmix.min-2.0.css')}}">
    <link href="{{asset('master/js/plugins/toastr/toastr.min.css')}}" rel="stylesheet">
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
    <script src="{{asset('master/js/plugins/toastr/toastr.min.js')}}"></script>
    @yield('script')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script>
            var notification = '{{ session()->get("success")}}';
            if(notification != ''){
                toastr_call("success","{{__('page.success')}}",notification);
            }
            var errors_string = '<?php echo json_encode($errors->all()); ?>';
            errors_string=errors_string.replace("[","").replace("]","").replace(/\"/g,"");
            var errors = errors_string.split(",");
            if (errors_string != "") {
                for (let i = 0; i < errors.length; i++) {
                    const element = errors[i];
                    toastr_call("error","{{__('page.error')}}",element);             
                } 
            }       
    
            function toastr_call(type,title,msg,override){
                toastr[type](msg, title,override);
                toastr.options = {
                    "closeButton": false,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": true,
                    "positionClass": "toast-top-center",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }  
            }
        </script>
</body>

</html>
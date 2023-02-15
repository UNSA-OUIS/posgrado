<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles Template-->
        <link rel="stylesheet" href="{{ asset(mix('assets/css/bootstrap.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/icons.css')) }}">
        <link rel="stylesheet" href="{{ asset(mix('assets/css/app.css')) }}">

        <!-- Styles -->
        <!--<link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">-->

        <!-- Scripts -->
        @routes
        <script src="{{ asset(mix('js/app.js')) }}" defer></script>     
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-5T79N9NBD2"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-5T79N9NBD2');
        </script>   
    <body>    
        @inertia
    </body>
</html>

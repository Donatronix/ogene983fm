<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Ogene98.3FM Radio Station">
        <meta name="keywords" content="Ogene98.3FM, Radio Station, online radio">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Ogene98.3FM | @yield('title')</title>
        <!-- Favicon -->
        <link href="{{ asset('images/logo.png') }}" rel="shortcut icon" />

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

        <!-- Css Styles -->
        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/themify-icons.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/elegant-icons.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/nice-select.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/slicknav.min.css') }}" type="text/css">
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" type="text/css">
        @stack('css')
    </head>

    <body>
        @include('layouts.pages.includes.navbar')

        @yield('content')

        @include('layouts.pages.includes.footer')
    </body>

</html>

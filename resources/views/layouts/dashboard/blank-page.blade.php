<!DOCTYPE html>
<html lang="en">

    <head>
        <meta name="description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
        <!-- Favicon -->
        <link href="{{ asset('images/logo.png') }}" rel="shortcut icon" />

        <!-- Twitter meta-->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:site" content="@ogenefm983">
        <meta property="twitter:creator" content="@ogenefm983">
        <!-- Open Graph Meta-->
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="Vali Admin">
        <meta property="og:title" content="Vali - Free Bootstrap 4 admin theme">
        <meta property="og:url" content="http://ogene983fm.com/blog/vali-admin">
        <meta property="og:image" content="http://ogene983fm.com/blog/vali-admin/hero-social.png">
        <meta property="og:description" content="Vali is a responsive and free admin theme built with Bootstrap 4, SASS and PUG.js. It's fully customizable and modular.">
        <title>Ogene98.3FM Dashboard || @yield('title')</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/main.css') }}">
        <!-- Font-icon css-->
        <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" />
        @stack('css')
    </head>

    <body class="app sidebar-mini rtl">
        <!-- Navbar-->
        @include('layouts.dashboard.includes.navbar')
        <!-- Sidebar menu-->
        @include('layouts.dashboard.includes.sidebar')
        @yield('content')

        <!-- Essential javascripts for application to work-->
        <script src="{{ asset('backend/js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('backend/js/popper.min.js') }}"></script>
        <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/js/main.js') }}"></script>
        <!-- The javascript plugin to display page loading on top-->
        <script src="{{ asset('backend/js/plugins/pace.min.js') }}"></script>
        <!-- Page specific javascripts-->
        <!-- Google analytics script-->
        <script type="text/javascript">
            if (document.location.hostname == 'ogene983fm.com') {
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
                ga('create', 'UA-72504830-1', 'auto');
                ga('send', 'pageview');
            }

        </script>
        @stack('js')
    </body>

</html>

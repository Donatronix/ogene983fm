<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Favicon -->
        <link href="{{ asset('images/logo.png') }}" rel="shortcut icon" />
        <!-- Main CSS-->
        <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/main.css') }}">
        <!-- Font-icon css-->
        <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}" />
        <title>Ogene98.3FM Dashboard || @yield('title')</title>
        @stack('css')
    </head>

    <body>
        <section class="material-half-bg">
            <div class="cover"></div>
        </section>
        <section class="lockscreen-content">
            <div class="logo">
                <h1>Vali</h1>
            </div>
            <div class="lock-box"><img class="rounded-circle user-image" src="https://s3.amazonaws.com/uifaces/faces/twitter/jsa/128.jpg">
                <h4 class="text-center user-name">John Doe</h4>
                <p class="text-center text-muted">Account Locked</p>
                <form class="unlock-form" action="index.html">
                    <div class="form-group">
                        <label class="control-label">PASSWORD</label>
                        <input class="form-control" type="password" placeholder="Password" autofocus>
                    </div>
                    <div class="form-group btn-container">
                        <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-unlock fa-lg"></i>UNLOCK </button>
                    </div>
                </form>
                <p><a href="page-login.html">Not John ? Login Here.</a></p>
            </div>
        </section>
        <!-- Essential javascripts for application to work-->
        <script src="{{ asset('backend/js/jquery-3.2.1.min.js') }}"></script>
        <script src="{{ asset('backend/js/popper.min.js') }}"></script>
        <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/js/main.js') }}"></script>
        <!-- The javascript plugin to display page loading on top-->
        <script src="{{ asset('backend/js/plugins/pace.min.js') }}"></script>
        @stack('js')
    </body>
</html>

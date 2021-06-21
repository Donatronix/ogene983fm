<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

@include('errors.list')
<!-- Header Section Begin -->
<header class="header-section">
    <div class="header-top">
        <div class="container">
            <div class="ht-left">
                <div class="mail-service">
                    <i class=" fa fa-envelope"></i>
                    info@ogene983fm.com
                </div>
                <div class="phone-service">
                    <i class=" fa fa-phone"></i>
                    +234 807 772 6636, +234 816 749 4172
                </div>
            </div>
            <div class="ht-right">
                @guest
                <a href="{{ route('login') }}" class="login-panel"><i class="fa fa-sign-in"></i>Login</a>
                <a href="{{ route('register') }}" class="login-panel"><i class="fa fa-user"></i>Register</a>
                @else
                <a href="{{ route('logout') }}" class="login-panel" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i>{{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @endguest
                @auth
                @role('admin|super admin')
                <a href="{{ route('dashboard') }}" class="login-panel"><i class="fa fa-briefcase"></i>Dashboard</a>
                @endrole
                @endauth
                <div class="top-social">
                    <a href="https://www.facebook.com/ogene983fm"><i class="ti-facebook"></i></a>
                    <a href="https://twitter.com/ogenefm983"><i class="ti-twitter-alt"></i></a>
                    <a href="https://instagram.com/ogene983fm"><i class="ti-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="inner-header">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div style="margin-bottom:30px;">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="btn btn-danger btn-lg" href="https://ogene983fm.radio12345.com/">
                                    Listen live <i class="fa fa-play-circle-o" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="advanced-search" style="width:100%;">
                        <form action="{{ route('search') }}" method="GET" class="input-group">
                            @csrf
                            <input name="search" type="text" placeholder="What do you need?">
                            <button type="button"><i class="ti-search"></i></button>
                        </form>
                    </div>
                </div>
                @livewire('on-air.on-air-programme')

            </div>
        </div>
    </div>
    <div class="nav-item">
        <div class="container">
            <nav class="nav-menu mobile-menu">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('metro.index') }}">Metro</a></li>
                    <li><a href="{{ route('discussion.index') }}">Today's Show</a></li>
                    <li><a href="{{ route('post.index') }}">News/Events</a>
                        @isset($categories)
                        <ul class="dropdown">
                            @foreach ($categories as $category)
                            <li><a href="{{ route('post.category', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                        @endisset
                    </li>

                    <li><a href="{{ route('gallery.album.index') }}">Gallery</a></li>
                    <li><a href="{{ route('about') }}">About</a>
                        <ul class="dropdown">
                            <li><a href="{{ route('programme.index') }}">Programmes</a></li>
                            <li><a href="{{ route('presenter.index') }}">OAP<small>s</small></a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
            <div id="mobile-menu-wrap"></div>
        </div>
    </div>
</header>

<!-- Header End -->
@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@livewireStyles
@endpush

@push('js')
<script>
    $(document).ready(function () {
        var currentProgramme;
        // refresh messages
        timerId = setTimeout(function tick() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });
            $.ajax({
                url: "{{ route('onAir') }}",
                type: "GET",
                success: function (result) {
                    var programme = result.image;
                    if (currentProgramme != programme) {
                        $("#onAir").attr("src", programme);
                        currentProgramme = programme;
                    }
                },
                complete: function () {
                    timerId = setTimeout(tick, 2000);
                },
                error: function (res) {
                    console.log("Error: " + res);
                }
            });
        }, 2000);
    });

</script>
@livewireScripts
@endpush

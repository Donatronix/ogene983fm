@extends('layouts.pages.login')
@section('title')
Login
@endsection
@section('content')

<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                    <span>Login</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Form Section Begin -->

<!-- Register Section Begin -->
<div class="register-login-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="login-form">
                    <h2>Login</h2>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="group-input">
                            <label for="email">Email address *</label>
                            <input type="text" name="email" value="{{ old('email') }}">
                            @error('email')
                            <span class="invalid-feedback has-errors" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="group-input">
                            <label for="password">Password *</label>
                            <input type="password" name="password" type="password" value="{{ old('password') }}">
                            @error('password')
                            <span class="invalid-feedback has-errors" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="group-input gi-check">
                            <div class="gi-more">
                                <label for="save-pass">
                                    {{ __('Remember Me') }}
                                    <input type="checkbox" name="remember" id="save-pass" {{ old('remember') ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>

                                @if (Route::has('password.request'))
                                <a class="forget-pass" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif

                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="site-btn login-btn">{{ __('Login') }}</button>
                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </div>
                        </div>
                    </form>
                    @if (Route::has('register'))
                    <div class="switch-login">
                        <a href="{{ route('register') }}" class="or-login">Or Create An Account</a>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Register Form Section End -->
@endsection

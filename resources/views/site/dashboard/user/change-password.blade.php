@extends('layouts.dashboard.page-login')
@section('title')
PharmacyTherapon || Dashboard - Change Password
@endsection
@section('content')
<section class="material-half-bg">
    <div class="cover"></div>
</section>
<section class="login-content">
    <div class="logo">
        <h1>PharmacyTherapon</h1>
    </div>
    <div class="login-box">
        <form class="login-form" action="{{ route('user.profile.updatePassword', ['user' => auth()->user()->slug]) }}">
            <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>CHANGE PASSWORD</h3>
            <div class="form-group">
                <label class="control-label">OLD PASSWORD</label>
                <input class="form-control" type="password" placeholder="Old Password" name="old_password">
            </div>
            <div class="form-group">
                <label class="control-label">NEW PASSWORD</label>
                <input class="form-control" type="password" placeholder="New Password" name="new_password">
            </div>
            <div class="form-group">
                <label class="control-label">CONFIRM NEW PASSWORD</label>
                <input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password">
            </div>
            <div class="form-group">
                <div class="utility">
                    <div class="animated-checkbox">
                        <label>
                            <input type="checkbox"><span class="label-text">Stay Signed in</span>
                        </label>
                    </div>
                    <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p>
                </div>
            </div>
            <div class="form-group btn-container">
                <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
            </div>
        </form>

    </div>
</section>
@endsection

@extends('layouts.dashboard.form-notifications')
@section('title')

@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-bell-o"></i> Form Notifications</h1>
            <p>Jquery Plugins to notify user status about some action.</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Form Notifications</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">Bootstrap Notify</h3>
                    <p><a class="btn btn-primary icon-btn" href="http://bootstrap-notify.remabledesigns.com/" target="_blank"><i class="fa fa-file"></i>Docs</a></p>
                </div>
                <div class="tile-body">
                    <p>This plugin can be used to notify user about status of some action which he has performed.</p>
                    <h4>Demo</h4><a class="btn btn-info" id="demoNotify" href="#">Sample Notification</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">SweetAlert</h3>
                    <p><a class="btn btn-primary icon-btn" href="http://t4t5.github.io/sweetalert/" target="_blank"><i class="fa fa-file"></i>Docs</a></p>
                </div>
                <div class="tile-body">
                    <p>This plugin can be used as the replacement of native javascript alert, confirm and prompt functions.</p>
                    <h4>Demo</h4><a class="btn btn-info" id="demoSwal" href="#">Sample Alert</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

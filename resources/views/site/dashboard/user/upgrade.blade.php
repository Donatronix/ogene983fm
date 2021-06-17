@extends('layouts.dashboard.form-samples')
@section('title')
{{ ucfirst($upgrade) }} Member Upgrade
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Upgrade</h1>
            <p>Upgrade your membership</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item">@yield('title')</li>
        </ul>
    </div>
    <div class="row">
        @include('errors.list')
    </div>
    <div class="row">
        <div class="col-md-12">
            <h4 class="text-center">
                Upgrade your membership to <span class="text-info"><b>{{ ucfirst($upgrade) }}</b></span> to be able
                to access the page.
            </h4>
        </div>
        <div class="col-md-12">
            <!-- multistep form -->
            <div id="msform">
                <!-- progressbar -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="tile">
                            <ul id="progressbar">
                                @foreach ($forms as $key => $form)
                                <li @if ($loop->first) class="active" @endif>
                                    <b style="color:black;">{{ $form['title'] }}</b>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- fieldsets -->
                @foreach ($forms as $key => $form)
                <fieldset>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tile">
                                <h3 class="tile-title">{{ $form['title'] }}</h3>
                                <p class="fs-subtitle">{{ $form['subTitle'] }}</p>
                                <div class="tile-body" style="padding:20px; width: 100%;">
                                    {!! $form['form'] !!}
                                </div>
                                <div class="tile-footer">
                                    @if (!$loop->first)
                                    <button type="button" name="previous" class="previous action-button pull-left" value=""><i
                                                            class="fa fa-fw fa-lg fa-arrow-circle-left"></i> Back
                                    </button>
                                    @endif
                                    @if (!$loop->last)
                                    <button type="button" name="next" class="next action-button pull-right">
                                        Next <i class="fa fa-fw fa-lg fa-arrow-circle-right"></i></button>
                                    @else
                                    <button type="submit" name="submit" class="submit action-button pull-right"><i
                                                            class="fa fa-fw fa-lg fa-arrow-circle-up"></i> Submit
                                    </button>
                                    @endif
                                    <div class="clearfix"></div>
                                    <a class="btn btn-danger" href="{{ url('/') }}">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </fieldset>
                @endforeach
                <fieldset style="display:none;">
                    <form action="{{ route('user.upgrade.membership',['user' => $user->slug,'role' => $upgrade]) }}" method="POST">
                        @csrf
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

</main>
@endsection


@push('css')
<style>
    /*custom font*/
    @import url(https://fonts.googleapis.com/css?family=Montserrat);


    body {
        font-family: montserrat, arial, verdana;
    }

    /*form styles*/
    #msform {
        width: 80%;
        margin: 50px auto;
        text-align: center;
        position: relative;
    }

    #msform fieldset {
        /* background: white;
            border: 0 none;
            border-radius: 3px;
            box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
            padding: 20px 30px;
            box-sizing: border-box;
            width: 80%;
            margin: 0 10%; */

        /*stacking fieldsets above each other*/
        position: relative;
    }

    /*Hide all except first fieldset*/
    #msform fieldset:not(:first-of-type) {
        display: none;
    }

    /*inputs*/
    #msform input,
    #msform select,
    #msform textarea {
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        margin-bottom: 10px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2c3e50;
        font-size: 13px;
    }

    /*buttons*/
    #msform .action-button {
        width: 100px;
        background: #27ae60;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 1px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 0px 5px;
    }

    #msform .action-button:hover,
    #msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #27ae60;
    }

    /*headings*/
    .fs-title {
        font-size: 15px;
        text-transform: uppercase;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .fs-subtitle {
        font-weight: normal;
        font-size: 13px;
        color: #666;
        margin-bottom: 20px;
    }

    /*progressbar*/
    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        /*CSS counters to number the steps*/
        counter-reset: step;
    }

    #progressbar li {
        list-style-type: none;
        color: white;
        text-transform: uppercase;
        font-size: 9px;
        width: 15%;
        float: left;
        position: relative;
    }

    #progressbar li:before {
        content: counter(step);
        counter-increment: step;
        width: 20px;
        line-height: 20px;
        display: block;
        font-size: 10px;
        color: #333;
        background: white;
        border-radius: 3px;
        margin: 0 auto 5px auto;
    }

    /*progressbar connectors*/
    #progressbar li:after {
        content: "";
        width: 100%;
        height: 2px;
        background: white;
        position: absolute;
        left: -50%;
        top: 9px;
        z-index: -1;
        /*put it behind the numbers*/
    }

    #progressbar li:first-child:after {
        /*connector not needed before the first step*/
        content: none;
    }

    /*marking active/completed steps green*/
    /*The number of the step and the connector before it = green*/
    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #27ae60;
        color: white;
    }

</style>
@endpush

@push('js')
<script src="{{ asset('backend/js/jquery.address.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script src="{{ asset('js/additional-methods.js') }}"></script>
<script src="{{ asset('backend/js/jquery.user-profile.js') }}"></script>
@endpush

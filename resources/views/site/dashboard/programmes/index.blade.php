@extends('layouts.dashboard.index')
@section('title')
Programmes
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Programmes</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('programme.dashboard') }}">Programmes</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('programme.create') }}"><i class="fa fa-plus"></i>New Programme </a></p>
        </div>

        @include('errors.list')
    </div>
    <div class="row">
        @foreach ($programmes as $programme)
        @php
        $time=$programme->programmeTimes;
        if ($time) {
        $time->each(function ($item, $key) {
        $item->day=$item->day.'s';
        });
        }
        @endphp
        <div class="col-md-4">
            <div class="tile">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $programme->coverImage }}" alt="" class="img-thumbnail img-responsive">
                    </div>
                    <div class="col-md-8">
                        <div class="tile-title-w-btn">
                            <h3 class="title">{{ ucwords($programme->title) }}</h3>
                        </div>
                        <div class="tile-body">
                            {!! nl2br($programme->about) !!}<br>
                            <strong>Days:</strong> {{ $time ? $time->implode('day', ', ') : null }} <br>
                            @php
                            $time=$time->first();
                            @endphp
                            <strong>Time:</strong> {{ $time ? date('h:i a', strtotime($time->from)) : null }} {{ $time ? "to ". date('h:i a', strtotime($time->to)) : null }}<br>
                        </div>
                    </div>
                </div>
                <div class="mt-2 btn-group">
                    <a class="btn btn-primary" href="{{ route('programme.edit', ['programme' => $programme->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('programme.delete', ['programme' => $programme->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('programme.delete', ['programme' => $programme->slug]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
        @endforeach





    </div>
</main>
@endsection

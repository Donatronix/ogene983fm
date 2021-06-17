@extends('layouts.dashboard.index')
@section('title')
Metro
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Metros</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('metro.dashboard') }}">Metro</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('metro.create') }}"><i class="fa fa-plus"></i>New Metro Article</a></p>
        </div>

        @include('errors.list')
    </div>
    <div class="row">
        @forelse ($metros as $metro)
        <div class="col-md-6">
            <div class="tile">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $metro->coverImage }}" alt="" class="img-responsive" style="width:100%;">
                    </div>
                    <div class="col-md-8">
                        <div class="tile-title-w-btn">
                            <h3 class="title">{{ $metro->title }}</h3>
                        </div>
                        <div class="tile-body">
                            <b>{!! nl2br($metro->about) !!} </b>
                        </div>
                        <p class="mt-2">
                            Author: <b>{{ $metro->author ?? null }}</b>
                        </p>
                        <p class="mt-2">
                            Created: <b>{{ $metro->created_at->toDayDateTimeString() ?? null }}</b><br />
                            Modified: <b>{{ $metro->updated_at->toDayDateTimeString() ?? null }}</br>
                        </p>
                    </div>
                </div>
                <div class="mt-2 btn-group">
                    {{-- <i class="btn btn-primary" href="#"><i class="fa fa-lg fa-plus"></i></i> --}}
                    <a class="btn btn-primary" href="{{ route('metro.edit', ['metro' => $metro->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('metro.delete', ['metro' => $metro->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('metro.delete', ['metro' => $metro->slug]) }}" method="METRO" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>

                </div>
            </div>
        </div>
        @empty

        @endforelse
    </div>
</main>
@endsection

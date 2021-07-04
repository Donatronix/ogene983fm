@extends('layouts.dashboard.index')
@section('title')
Presenters
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Presenters</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('presenter.dashboard') }}">Presenters</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('presenter.create') }}"><i class="fa fa-plus"></i>New Presenter </a></p>
        </div>

        @include('errors.list')
    </div>
    <div class="row">
        @forelse ($presenters as $presenter)
        <div class="col-md-6">
            <div class="tile">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $presenter->coverImage }}" alt="" class="img-thumbnail img-responsive">
                    </div>
                    <div class="col-md-8">
                        <div class="tile-title-w-btn">
                            <h3 class="title">{{ ucwords($presenter->name) }}</h3>
                        </div>
                        <div class="tile-body">
                            {!! $presenter->about !!}
                        </div>
                    </div>
                </div>
                <div class="mt-2 btn-group">
                    {{-- <i class="btn btn-primary" href="#"><i class="fa fa-lg fa-plus"></i></i> --}}
                    <a class="btn btn-primary" href="{{ route('presenter.edit', ['presenter' => $presenter->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>
                    @role('admin')
                    <a class="btn btn-danger" href="{{ route('presenter.delete', ['presenter' => $presenter->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('presenter.delete', ['presenter' => $presenter->slug]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endrole
                </div>
            </div>
        </div>
        @empty

        @endforelse





    </div>
</main>
@endsection

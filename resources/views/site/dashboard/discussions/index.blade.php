@extends('layouts.dashboard.index')
@section('title')
Discussions
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Discussions</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('discussion.dashboard') }}">Discussions</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('discussion.create') }}"><i class="fa fa-plus"></i>New Discussion </a></p>
        </div>

        @include('errors.list')
    </div>
    <div class="row">
        @forelse ($discussions as $discussion)
        <div class="col-md-6">
            <div class="tile">
                <div class="tile-title-w-btn">
                    <h3 class="title">{{ $discussion->title }}</h3>
                </div>

                <div class="tile-body row">
                    <div class="col-md-4">
                        <img class="img-fluid" src="{{ asset($discussion->coverImage) }}" alt="{{ $discussion->title }}">
                    </div>
                    <div class="col-md-8">
                        <b>{!! nl2br($discussion->about) !!} </b>
                        <br />
                        <p>
                            <b>Programme:</b> {{ $discussion->programmeName }}<br>
                            @php
                            $presenters=[];
                            foreach ($discussion->presenters as $key => $presenter) {
                            $presenters[]=$presenter->name;
                            }
                            @endphp
                            <b>{{ \Illuminate\Support\Str::plural('Presenter', count($presenters)) }}:</b> {{ implode(', ', $presenters) }}
                        </p>

                        <div class="mt-2 btn-group">
                            <a class="btn btn-primary" href="{{ route('discussion.edit', ['discussion' => $discussion->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>
                            <a class="btn btn-danger" href="{{ route('discussion.delete', ['discussion' => $discussion->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form{{ $loop->index }}').submit();">
                                <i class="fa fa-lg fa-trash"></i>
                            </a>
                            <form id="delete-form{{ $loop->index }}" action="{{ route('discussion.delete', ['discussion' => $discussion->slug]) }}" method="DISCUSSION" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            @empty

            @endforelse
        </div>
</main>
@endsection

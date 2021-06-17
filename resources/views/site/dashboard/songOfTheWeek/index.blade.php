@extends('layouts.dashboard.index')
@section('title')
Posts
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Posts</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('post.dashboard') }}">Posts</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('post.create') }}"><i class="fa fa-plus"></i>New Post </a></p>
        </div>

        @include('errors.list')
    </div>
    <div class="row">
        @forelse ($posts as $post)
        <div class="col-md-6">
            <div class="tile">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $post->coverImage }}" alt="" class="img-thumbnail img-responsive">
                    </div>
                    <div class="col-md-8">
                        <div class="tile-title-w-btn">
                            <h3 class="title">{{ $post->title }}</h3>
                        </div>
                        <div class="tile-body">
                            <b>{!! nl2br($post->about) !!} </b>
                        </div>
                    </div>
                </div>
                <div class="btn-group mt-2">
                    {{-- <i class="btn btn-primary" href="#"><i class="fa fa-lg fa-plus"></i></i> --}}
                    <a class="btn btn-primary" href="{{ route('post.edit', ['post' => $post->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('post.delete', ['post' => $post->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('post.delete', ['post' => $post->slug]) }}" method="POST" style="display: none;">
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

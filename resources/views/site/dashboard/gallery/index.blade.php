@extends('layouts.dashboard.index')
@section('title')
@isset($type)
{{ "$album->title Album"  }}
@else
Gallery
@endisset
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1>
                <i class="fa fa-dashboard"></i>
                @isset($type)
                {{ "$album->title Album"  }}
                @else
                Gallery
                @endisset
            </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery.album.dashboard') }}">Gallery</a></li>
            @isset($type)
            <li class="breadcrumb-item"><a href="">{{ $album->title }}</a></li>
            @endisset
        </ul>
    </div>
    <div class="row">
        @isset($type)
        <div class="col-md-6 col-lg-3 pull-right">
            <p><a class="btn btn-primary icon-btn" href="{{ route('gallery.album.upload.create', ['album' => $album->slug]) }}"><i class="fa fa-plus"></i>Add Album Item </a></p>
        </div>
        @else
         <div class="col-md-6 col-lg-3 pull-right">
             <p><a class="btn btn-primary icon-btn" href="{{ route('gallery.album.create') }}"><i class="fa fa-plus"></i>Add Album </a></p>
         </div>
        @endisset

        @include('errors.list')
    </div>
    <div class="row">
        @forelse ($albums as $album)
        <div class="col-md-6">
            <div class="tile">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $album->coverImage }}" alt="" class="img-thumbnail img-responsive">
                    </div>
                    <div class="col-md-8">
                        <div class="tile-title-w-btn">
                            <h3 class="title">{{ $album->title }}</h3>
                        </div>
                        <div class="tile-body">
                            <b>{!! nl2br($album->about) !!} </b>
                        </div>
                    </div>
                </div>
                <div class="btn-group mt-2">
                    @empty($type)
                    <a class="btn btn-primary" href="{{ route('gallery.album.showAlbum', ['album' => $album->slug]) }}"><i class="fa fa-lg fa-eye"></i></a>
                    @endempty

                    <a class="btn btn-info" href="{{ route('gallery.album.edit', ['album' => $album->slug]) }}"><i class="fa fa-lg fa-edit"></i></a>

                    @isset($type)
                    <a class="btn btn-danger" href="{{ route('gallery.album.upload.delete', ['albumUpload' => $album->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('gallery.album.upload.delete', ['albumUpload' => $album->slug]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @else
                    <a class="btn btn-danger" href="{{ route('gallery.album.delete', ['album' => $album->slug]) }}" onclick="event.preventDefault();
                                                     document.getElementById('delete-form').submit();">
                        <i class="fa fa-lg fa-trash"></i>
                    </a>
                    <form id="delete-form" action="{{ route('gallery.album.delete', ['album' => $album->slug]) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endisset
                </div>
            </div>
        </div>
        @empty

        @endforelse





    </div>
</main>
@endsection

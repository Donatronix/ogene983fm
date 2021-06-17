@extends('layouts.dashboard.form-components')
@section('title')
New Album Item
@endsection
@php
use App\Helpers\Helper;
$helper= new Helper;
@endphp
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> {{ $helper->uppercaseWords($album->title) }}</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">{{ $helper->uppercaseWords($album->title) }}</li>
            <li class="breadcrumb-item"><a href="#">New</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="row">
                    @include('errors.list')
                    <div class="container">
                        <form action="{{ route('gallery.album.upload.store', ['album' => $album->slug]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputTitle">Title</label>
                                <input name="title" class="form-control" id="exampleInputTitle" type="text" placeholder="Enter title" value="{{ old('title') }}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="about">About</label>
                                <textarea name="about" class="form-control" id="about" rows="3" style="resize:none;">{{ old('about') }}</textarea>
                                @error('about')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Upload</label>
                                <input name="upload" class="form-control-file" id="exampleInputFile" type="file" aria-describedby="fileHelp" required>
                                @error('upload')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="tile-footer">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

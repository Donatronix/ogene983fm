@extends('layouts.dashboard.form-components')
@section('title')
New Category
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Categories</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.dashboard') }}">Categories</a></li>
            <li class="breadcrumb-item">New</li>
        </ul>
    </div>
    @include('errors.list')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                    <div class="tile-body">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName">Name</label>
                            <input name="name" class="form-control" id="exampleInputName" type="text" placeholder="Enter category name" value="{{ old('name') }}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea">Description</label>
                            <textarea name="description" placeholder="Enter category description" class="form-control" id="exampleTextarea" rows="3" style="resize:none;">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                            @enderror
                        </div>

                        @include('layouts.dashboard.includes.cover_image',['image' => 'cover_image'])
                        <div class="form-group">
                            <label for="category_id">Select root category </label>
                            {!! Form::select('category_id', $categories, old('category_id'), ["class" => "form-control", 'placeholder' => 'Select the root category']) !!}
                            @error('category_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@extends('layouts.dashboard.form-components')
@section('name')
Edit Category
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
            <li class="breadcrumb-item">{{ $category->name }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ul>
    </div>
    @include('errors.list')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <form action="{{ route('category.update', ['category' => $category->slug]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="exampleInputName">Name</label>
                        <input name="name" class="form-control" id="exampleInputName" type="text" placeholder="Enter category name" value="{{ old('name', $category->name) }}">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" placeholder="Enter category description" class="form-control" id="description" rows="3" style="resize:none;">{{ old('description', $category->about) }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="exampleInputFile">Cover image</label>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ $category->cover_image }}" alt="" class="img-thumbnail">
                            </div>
                            <div class="col-md-8">
                                <div id="image-preview" style="height:300px;">
                                    {{ Form::label('cover_image', null,['class' => 'btn btn-success','id'=>"image-label", 'style'=>'bottom:0; margin-bottom:20px;']) }}
                                    {{ Form::file("cover_image",[ 'id'=>'cover_image','style'=>'display:none;'] ) }}
                                </div>
                                @error('cover_image')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{!! $message !!}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category_id">Select root category </label>
                        {!! Form::select('category_id', $categories, old('category_id', $category->category_id), ["class" => "form-control", 'required' =>'']) !!}
                    </div>

                    <div class="tile-footer">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection


@push('js')
<script src="{{ asset('backend/js/jquery.uploadPreview.js') }}"></script>

<script type="text/javascript">
    jQuery(function () {
        $.uploadPreview({
            input_field: "#cover_image", // Default: .image-upload
            preview_box: "#image-preview", // Default: .image-preview
            label_field: "#image-label", // Default: .image-label
            label_default: "Upload cover image", // Default: Choose File
            label_selected: "Change Image", // Default: Change File
            no_label: false // Default: false
        });
    });

</script>
@endpush

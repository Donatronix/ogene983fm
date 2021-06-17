@extends('layouts.dashboard.form-components')
@section('title')
Edit Metro Article
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Metro</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('metro.dashboard') }}">Metro</a></li>
            <li class="breadcrumb-item">{{ $metro->title }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="p-0 tile">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Article</a></li>
                    <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Uploads</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="tile">
                        <h4 class="line-head">Article</h4>
                        <div class="row">
                            @include('errors.list')
                            <div class="container">
                                <form action="{{ route('metro.update', ['metro' => $metro->slug]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label for="author">Author</label>
                                        <input name="author" class="form-control" id="author" type="text" placeholder="Enter author" value="{{ old('author', $metro->author) }}">
                                        @error('author')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="category">Select Category</label>
                                        {!! Form::select('category', $categories, old('category', $metro->category ? $metro->category->id : null), ["class" => "form-control", 'required' =>'']) !!}
                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputTitle">Title</label>
                                        <input name="title" class="form-control" id="exampleInputTitle" type="text" placeholder="Enter title" value="{{ old('title', $metro->title) }}">
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputFile">Cover image</label>
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{ $metro->cover_image }}" alt="" class="img-thumbnail">
                                            </div>
                                            <div class="col-md-8">
                                                <div id="image-preview" style="height:300px;">
                                                    {{ Form::label('cover_image', null,['class' => 'btn btn-success','id'=>"image-label", 'style'=>'bottom:0; margin-bottom:20px;']) }}
                                                    {{ Form::file("cover_image",[ 'id'=>'cover_image','style'=>'display:none;'] ) }}
                                                </div>
                                                @error('cover_image')
                                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="summary">Summary</label>
                                        <textarea name="summary" class="form-control" id="summary" rows="3" style="resize:none;">{{ old('summary', $metro->about) }}</textarea>
                                        @error('summary')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="summary">Content/Body</label>
                                        <textarea name="content" class="form-control ckeditor" id="content" rows="3" style="resize:none;">{{ old('content', $metro->content) }}</textarea>
                                        @error('content')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="clear-fix"></div>
                                    <div class="tile-footer">
                                        <button class="btn btn-primary pull-left" type="submit">Submit</button>
                                        <a class="btn btn-danger pull-right" href="{{ route('metro.dashboard') }}">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-settings">
                    @livewire('dashboard.upload.upload-file', ['model' => $metro], key($metro->id))
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('css')
@livewireStyles
@endpush

@push('js')
@livewireScripts

<script src="{{ asset('backend/js/jquery.uploadPreview.js') }}"></script>
<script src=" {{ asset('vendor/editor/CKEDITOR.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
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

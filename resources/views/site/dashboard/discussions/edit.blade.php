@extends('layouts.dashboard.form-components')
@section('title')
Edit Discussion
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Discussions</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('discussion.dashboard') }}">Discussions</a></li>
            <li class="breadcrumb-item">{{ $discussion->title }}</li>
            <li class="breadcrumb-item">Edit</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="p-0 tile">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Discussion</a></li>
                    <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Uploads</a></li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="tile">
                        <h4 class="line-head">Discussion</h4>
                        <div class="row">
                            @include('errors.list')
                            <div class="container">
                                <form action="{{ route('discussion.update', ['discussion' => $discussion->slug]) }}" method="DISCUSSION" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="exampleInputTitle">Title</label>
                                        <input name="title" class="form-control" id="exampleInputTitle" type="text" placeholder="Enter title" value="{{ old('title', $discussion->title) }}">
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
                                                <img src="{{ $discussion->cover_image }}" alt="" class="img-thumbnail">
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
                                        <label for="programme">Select Programme</label>
                                        {!! Form::select('programme', $programmes, old('programme', $discussion->programme_id), ["class" => "form-control", 'required' =>'']) !!}
                                        @error('programme')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="summary">Summary</label>
                                        <textarea name="summary" class="form-control" id="summary" rows="3" style="resize:none;">{{ old('summary', $discussion->about) }}</textarea>
                                        @error('summary')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="summary">Content/Body</label>
                                        <textarea name="content" class="form-control ckeditor" id="content" rows="3" style="resize:none;">{{ old('content', $discussion->content) }}</textarea>
                                        @error('content')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="tile-footer">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                        <a class="btn btn-danger pull-right" href="{{ route('discussion.dashboard') }}">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-settings">
                    @livewire('dashboard.upload.upload-file', ['model' => $discussion], key($discussion->id))
                </div>
            </div>
        </div>
    </div>

</main>
@endsection

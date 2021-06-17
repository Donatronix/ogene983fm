@extends('layouts.dashboard.form-components')
@section('title')
New Programme
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> <a href="{{ route('programme.dashboard') }}">Programmes</a></h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i></a></li>
            <li class="breadcrumb-item"><a href="{{ route('programme.dashboard') }}">Programmes</a></li>
            <li class="breadcrumb-item">New</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="row">
                    @include('errors.list')
                    <div class="container">
                        <form action="{{ route('programme.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputTitle">Programme Title</label>
                                <input name="title" class="form-control" id="exampleInputTitle" type="text" placeholder="Enter programme title" value="{{ old('title') }}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Description</label>
                                <textarea name="description" class="form-control" id="exampleTextarea" rows="3" style="resize:none;">{{ old('description') }}</textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="exampleSelectDays">Select programme day(s)</label>
                                <ul id="exampleSelectDays" class="list-unstyled list-inline">
                                    @foreach ($days as $day)
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="programmeDay[{{ $loop->index }}]" value="{{ $day->day }}" type="checkbox" {{ old("programmeDay.$loop->index")==$day ? 'checked' : null }}>{{ ucfirst($day->day) }}
                                            </label>
                                            @error("programmeDay.$loop->index")
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="selectPresenters">Select presenter(s)</label>
                                <ul id=selectPresenters class="list-unstyled list-inline">
                                    @foreach ($presenters as $presenter)
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" name="presenters[{{ $loop->index }}]" value="{{ $presenter->slug }}" type="checkbox" {{ old("presenters.$loop->index")==$presenter->slug ? 'checked' : null }}>{{ ucwords($presenter->name) }}
                                            </label>
                                            @error("presenters.$loop->index")
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="from">Start time</label>
                                <div style="width:20%;">
                                    {!! Form::time('from', old('from',\Carbon\Carbon::now()->toTimeString()), ["class" => "form-control","required" => ""]) !!}
                                </div>
                                @error('from')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="to">Stop time</label>
                                <div style="width:20%;">
                                    {!! Form::time('to', old('to',\Carbon\Carbon::now()->toTimeString()), ["class" => "form-control","required" => ""]) !!}
                                </div>
                                @error('to')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
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

                            <div class="tile-footer">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a class="btn btn-danger pull-right" href="{{ route('programme.dashboard') }}">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('js')
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

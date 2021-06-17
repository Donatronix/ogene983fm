@extends('layouts.dashboard.form-custom')
@section('title')
Edit Profile Image
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-dashboard"></i> Profile Image</h1>
        <p>Update profile image</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.myProfile', ['user' => $user->slug]) }}"> Profile</a>
        </li>
        <li class="breadcrumb-item">Profile Image</li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        @include('errors.list')
        <div class="tile">
            <form action="{{ route('user.avatar.update', ['user' => $user->slug]) }}" method="POST" enctype="multipart/form-data">
                <div class="tile-body">
                    {{-- @method('PUT') --}}
                    @csrf
                    <div class="row mb-40">
                        <div class="col-md-4">
                            <img src="{{ $user->profileImage }}" alt="" class="img-responsive w-100">
                        </div>
                        <div class="col-md-8 mb-20">
                            <div id="image-preview" style="height:200px;">
                                {{ Form::label("profile_image", null,['class' => 'btn btn-success','id'=>"image-label", 'style'=>'bottom:0; margin-bottom:20px;']) }}
                                {{ Form::file("profile_image",[ 'id'=>"profile_image",'style'=>'display:none;'] ) }}
                            </div>
                            @error("profile_image")
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                            @enderror
                            @push('js')
                            <script src="{{ asset('backend/js/jquery.uploadPreview.js') }}"></script>

                            <script type="text/javascript">
                                jQuery(function () {
                                    $.uploadPreview({
                                        input_field: "#profile_image", // Default: .image-upload
                                        preview_box: "#image-preview", // Default: .image-preview
                                        label_field: "#image-label", // Default: .image-label
                                        label_default: "Upload cover image", // Default: Choose File
                                        label_selected: "Change Image", // Default: Change File
                                        no_label: false // Default: false
                                    });
                                });

                            </script>
                            @endpush
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <input type="submit" class="btn btn-success btn-lg" value="Update">
                    <a href="{{ url()->previous() }}" class="btn btn-danger btn-lg pull-right">Close</a>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

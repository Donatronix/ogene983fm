@extends('layouts.dashboard.form-custom')
@section('title')
PharmacyTherapon || Dashboard - About
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-user"></i> Edit About</h1>
        <p>Edit member about settings</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                Dashboard</a></li>
        <li class="breadcrumb-item">About</li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <form action="{{ route('user.about.update', ['user' => $user->slug]) }}" method="POST" class="w-100">
                <div class="tile-body">
                    @method('PUT')
                    @csrf
                    <div class="row mb-20">
                        <div class="col-md-12">
                            <div class="form-group">
                                {{Form::textarea("description", old("description",$user->about),
                                                [
                                                    "placeholder" => "About",
                                                    "class" => "form-control",
                                                    "required"    => "required",
                                                    'style'       => 'resize:none',
                                                ])
                                            }}
                                @error('description')
                                <div class="invalid-feedback text-danger" role="alert">
                                    <strong>{!! $message !!}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div><!-- .modal-body -->
                <div class="tile-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ url()->previous() }}" class="btn btn-danger pull-right">Close</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

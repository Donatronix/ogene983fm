@extends('layouts.dashboard.form-components')
@section('title')
New Programme Shedule
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-edit"></i> Programmes</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item">Programmes</li>
            <li class="breadcrumb-item"><a href="#">New</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="row">
                    @include('errors.list')
                    <div class="container">
                        <form action="{{ route('programme.time.store', ['programme' => $programme->slug]) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="exampleSelectDays">Select programme day</label>
                                {!! Form::select('programmeDay', $days ?? null, old('programmeDay'), ["id"=>"exampleSelectDays", "class" => "form-control", 'required' =>'']) !!}
                                @error('programmeDay')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="from">Enter programme start time (24hrs)</label>
                                {!! Form::time('from', old('from'), ["class" => "form-control","required" => ""]) !!}
                                @error('from')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="to">Enter programme stop time (24hrs)</label>
                                {!! Form::time('to', old('to'), ["class" => "form-control","required" => ""]) !!}
                                @error('to')
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

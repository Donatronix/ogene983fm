@extends('layouts.dashboard.form-custom')
@section('title')
PharmacyTherapon || Dashboard - Edit Settings }}
@endsection
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Edit Settings</h1>
            <p>Edit member profile settings</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item">{{ $title }}</li>
        </ul>
    </div>

    <div class="row">
        {!! $edit !!}
    </div>
</main>
@endsection

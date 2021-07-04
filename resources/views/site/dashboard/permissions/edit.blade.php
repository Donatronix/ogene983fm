@extends('layouts.dashboard.index')

@section('title', '| Edit Permission')

@section('breadcrumb')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="#">Permissions</a></li>
                            <li class="active">Edit Permission</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12">
        @include('errors.list')
        <div class="card">
            <div class="card-body card-block">

                <h1><i class='fa fa-pencil'></i> Edit {{$permission->name}}</h1>
                <hr>
                {{ Form::model($permission, array('route' => array('permissions.update', $permission->id), 'method' => 'PUT')) }}{{-- Form model binding to automatically populate our fields with permission data --}}

                <div class="form-group mb-5">
                    {{ Form::label('name', 'Permission Name') }}
                    {{ Form::text('name', null, array('class' => 'form-control')) }}
                </div>
                <hr>
                {{ Form::submit('Update', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}

            </div>
        </div>
    </div>
</div>
@endsection

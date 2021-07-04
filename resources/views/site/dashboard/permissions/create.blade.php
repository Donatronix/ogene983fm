@extends('layouts.dashboard.index')

@section('title', '| Create Permission')

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
                            <li class="active">Create Permission</li>
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
    <div class="col-md-12">
        @include('errors.list')
        <div class="card">
            <div class="card-body card-block">
                <h1><i class='fa fa-key'></i> Add Permission</h1>
                <hr>
                {{ Form::open(array('url' => 'permissions')) }}
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', '', array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    @if(!$roles->isEmpty())
                    <h4>Assign Permission to Roles</h4>

                    @foreach ($roles as $role)
                    {{ Form::checkbox('roles[]',  $role->id ) }}
                    {{ Form::label($role->name, ucfirst($role->name)) }}<br>
                    @endforeach
                    @endif
                </div>
                <hr>
                {{ Form::submit('Add', array('class' => 'btn btn-primary')) }}

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection

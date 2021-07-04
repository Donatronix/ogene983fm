@extends('layouts.dashboard.index')

@section('title', '| Permissions')

@section('breadcrumb')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="m-0 row">
            <div class="col-sm-4">
                <div class="float-left page-header">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="float-right page-header">
                    <div class="page-title">
                        <ol class="text-right breadcrumb">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="#">Permissions</a></li>
                            <li class="active">Permissions</li>
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
    <div class="col-lg-12">
        @include('errors.list')
        <div class="card">
            <div class="card-body">
                <h1>
                    <i class="fa fa-key"></i>Available Permissions

                    <div class="pull-right">
                        <a href="{{ route('users.index') }}" class="btn btn-success">Users</a>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary">Roles</a>
                        <a href="{{ route('permissions.create') }}" class="btn btn-info">Add Permission</a>
                    </div>
                </h1>
                <hr>
                <div class="mt-4 table-responsive">
                    <table class="table table-bordered table-striped table-hovered">
                        <thead>
                            <tr>
                                <th class="serial">#</th>
                                <th>Permissions</th>
                                <th>Operation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                            <tr>
                                <td class="serial">{{ $loop->iteration }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <a href="{{ route('permissions.edit',$permission->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger pull-right']) !!}
                                    {!! Form::close() !!}

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.dashboard.table-data-table')
@php
use App\Helpers\Helper;
$helper = new Helper;
@endphp

@push('css')
<style>
    table thead th {
        font-weight: bold;
    }

</style>
@endpush
@section('title', 'Roles')

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
                            <li><a href="#">Roles</a></li>
                            <li class="active">Roles</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            @include('errors.list')
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <h1 class="pull-left"><i class="fa fa-key"></i> Roles</h1>
                        <div class="pull-right">
                            <a href="{{ route('users.index') }}" class="btn btn-success mr-1">Users</a>
                            <a href="{{ route('permissions.index') }}" class="btn btn-primary mr-1">Permissions</a>
                            <a href="{{ route('roles.create') }}" class="btn btn-info">Add Role</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hovered">
                            <thead>
                                <tr>
                                    <th class="serial">#</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td class="serial">{{ $loop->iteration }}</td>
                                    <td>{{ $helper->uppercaseWords($role->name) }}</td>

                                    <td>{{ str_replace(array('[',']','"'),'', $role->permissions()->pluck('name')) }}</td>
                                    {{-- Retrieve array of permissions associated to a role and convert to string --}}
                                    <td>
                                        <a href="{{ route('roles.edit',$role->id) }}" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>

                                        {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id] ]) !!}
                                        {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
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
</div>
@endsection

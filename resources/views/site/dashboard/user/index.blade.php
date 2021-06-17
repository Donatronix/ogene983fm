@extends('layouts.dashboard.table-basic')
@section('title')
PharmacyTherapon || Dashboard - Members
@endsection
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-users"></i> Members</h1>
            <p>Members Details</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                    <i class="fa fa-home fa-lg"></i>
                    Dashboard
                </a>
            </li>
            <li class="breadcrumb-item">Members</li>
        </ul>
    </div>

    <div class="row">
        @include('errors.list')
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>About</th>
                                <th>Roles</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            @continue((auth()->user()->isSuperAdmin==false) && ($user->isSuperAdmin==true))

                            <tr class="{{ ($user->online==true) ? 'table-success' : 'table-danger' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $user->avatar }}" alt="" class="img-responsive img-thumbnail">
                                </td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->about }}</td>
                                <td>{{ $user->user_roles }}</td>
                                <td>
                                    <ul class="list-inline list-unstyled">
                                        <li>
                                            <a href="#" data-url={{ route('roles.edit.user',['user' => $user->slug]) }} class="m-1 edit_role btn btn-primary">Roles</a>
                                        </li>
                                        <li>
                                            <a href="#" data-url="{{ route('permissions.edit.user', ['user' => $user->slug]) }}" class="m-1 edit_permission btn btn-warning">Permissions</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('user.profile', ['user'=> $user->slug]) }}" class="m-1 btn btn-info">View Profile</a>
                                        </li>
                                        @if (auth()->user()->isSuperAdmin && empty($user->email_verified_at))
                                        <li>
                                            <a href="{{ route('user.verify', ['user'=> $user->slug]) }}" class="m-1 btn btn-secondary">Verify User</a>
                                        </li>
                                        @endif
                                        <li>
                                            <a class="m-1 btn btn-danger" href="{{ route('user.delete', ['user'=> $user->slug]) }}" onclick="event.preventDefault(); document.getElementById('delete-{{ $loop->iteration }}').submit();">
                                                {{ __('Delete') }}
                                            </a>
                                            <form id="delete-{{ $loop->iteration }}" action="{{ route('user.delete', ['user'=> $user->slug]) }}" method="POST" style="display: none;">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="my-modal"></div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('js')
<script src="{{ asset('backend/js/jquery.user.js') }}"></script>
@endpush

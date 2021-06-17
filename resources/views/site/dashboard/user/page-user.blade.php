@extends('layouts.dashboard.page-user')
@section('title')
{{ $user->name }} - Profile
@endsection
@php
use App\Helpers\Helper;
$helper= new Helper;
@endphp
@section('content')
<main class="app-content">
    <div class="row user">
        <div class="col-md-12">
            <div class="profile">
                <div class="info">
                    <h4>{{ $user->name }}</h4>
                    <p>{{ $user->userRoles }}</p>
                </div>
                <div class="cover-image">
                    <img src="{{ $user->profileImage }}" alt="" class="img-responsive" style="width: 50%;">
                    <a href="{{ route('user.avatar.edit', ['user' => $user]) }}" class="btn btn-primary edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-3">
            <div class="tile p-0">
                <ul class="nav flex-column nav-tabs user-tabs">
                    <li class="nav-item"><a class="nav-link active" href="#user-timeline" data-toggle="tab">Timeline</a></li>
                    <li class="nav-item"><a class="nav-link" href="#user-settings" data-toggle="tab">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="user-timeline">
                    <div class="timeline-post">
                        <div class="post-content">
                            <h2 class="line-head">Platforms</h2>
                            <div class="row">
                                @forelse ($items as $item)
                                <div class="col-md-6 col-lg-4 equalHeight mb-4">
                                    <div class="widget-small primary coloured-icon">
                                        @isset($item['image'])
                                        <img src="{{ $item['image'] }}" alt="" class="icon img-responsive equalHeight" style="width:35%;">
                                        @else
                                        <i class="icon fa fa-users fa-3x equalHeight" style="width:35%;"></i>
                                        @endisset
                                        <div class="info">
                                            <h4 style="text-transform: none;">{{ $item['title'] }}</h4>
                                            <p><b>{!! $item['count'] !!}</b></p>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-md-12">
                                    <p> You have not uploaded anything on any of our platforms.</p>
                                </div>
                                @endforelse
                            </div>
                            @if(count($charts) > 0)
                            <div class="row py-3 px-3">
                                @foreach ($charts as $chart)
                                <div class="col-md-6">
                                    {!! $chart->container() !!}
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="user-settings">
                    <div class="tile user-settings">
                        <h2 class="line-head">Profile Settings</h2>
                        <div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label>About</label>
                                    <p>{!! nl2br($user->about )!!}</p>
                                    <a class="btn btn-primary edit" href="{{ route('user.about.edit', ['user' => $user->slug]) }}"><i
                                                    class="fa fa-edit"></i></a>
                                </div>
                            </div>

                            <div class="row mb-4">
                                @if ($user->isExpert)
                                @if ($user->registration)
                                <div class="col-md-4">
                                    <label>Registration details</label>
                                    <p>
                                        Number: {{ $user->registration->registration_number }} <br />
                                        Body: {{ $user->registration->registration_body }} <br />
                                        Country: {{ $user->registration->country }} <br />
                                    </p>
                                    <a class="btn btn-primary edit" href="{{ route('user.registration.edit', ['user' => $user->slug, 'registration' => $registration->slug]) }}"><i
                                                            class="fa fa-edit"></i></a>
                                </div>
                                @endif

                                @if ($user->licenses)
                                <div class="col-md-4">
                                    <label>License details</label>
                                    <ol>
                                        @foreach ($user->licenses as $license)
                                        <li>
                                            Number: {{ $license->license_number }} <br />
                                            Body: {{ $license->license_body }} <br />
                                            Country: {{ $license->license_country }} <br />
                                            Year: {{ $license->license_year }} <br />
                                            <a class="btn btn-primary edit" href="{{ route('user.license.edit', ['user' => $user->slug, 'license' => $license->slug]) }}"><i
                                                                        class="fa fa-edit"></i></a>
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>
                                @endif

                                @if ($user->qualification)
                                @php
                                $qualification = $user->qualification;
                                @endphp
                                <div class="col-md-4">
                                    <label>Qualification</label>
                                    {{ $qualification->qualification }} {{ $qualification->value ? " :$qualification->value" : null }}
                                    <br />
                                    <a class="btn btn-primary edit" href="{{ route('user.qualification.edit', ['user' => $user->slug]) }}"><i
                                                            class="fa fa-edit"></i></a>
                                </div>
                                @endif
                                @endif

                                @if ($user->isCorporate)
                                @isset ($user->pharmacists)
                                <div class="row">
                                    <h5>Pharmacist Staff</h5>
                                    @foreach ($user->pharmacists as $pharmacist)
                                    <div class="col-md-6">
                                        <label>{{ $pharmacist->designation }}</label>
                                        <div>
                                            Name: {{ $pharmacist->name }} <br />
                                            Registration Number: {{ $pharmacist->registration_number }}
                                            <br />
                                            License Number: {{ $pharmacist->license_number }} <br />
                                            Phone Number: {{ $pharmacist->phone_number }} <br />
                                            Email: {{ $pharmacist->email }} <br />
                                            <a class="btn btn-primary edit" href="{{ route('user.pharmacist.edit', ['user' => $user->slug, 'pharmacist' => $pharmacist->slug]) }}"><i
                                                                        class="fa fa-edit"></i></a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endisset
                                @endif

                                @if ($user->isExpert)
                                @if ($user->specialisations)
                                <div class="col-md-4">
                                    <label>{{ \Illuminate\Support\Str::plural('Specialisation', $user->specialisations->count()) }}</label>
                                    <ol class="list-inline">
                                        @foreach ($user->specialisations as $specialisation)
                                        <li>
                                            {{ $specialisation->name }}
                                        </li>
                                        @endforeach
                                    </ol>
                                    <a class="btn btn-primary edit" href="{{ route('user.specialisation.edit', ['user' => $user->slug]) }}"><i
                                                            class="fa fa-edit"></i></a>
                                </div>
                                @endif
                                @endif
                            </div>
                            <div class="row">
                                @if ($user->isExpert)
                                @isset($user->journals)
                                <div class="col-md-8 mb-4">
                                    <label>Journals</label>
                                    @foreach ($user->journals as $journal)
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{ $journal->coverImage }}" alt="" class="img-thumbnail">
                                            </div>
                                            <div class="col-md-8">
                                                Title: {{ $journal->title }} <br />
                                                Description: {{ $journal->about }} <br />
                                            </div>
                                            <div class="btn-group">
                                                <a href="{{ $journal->downloadLink }}" class="btn btn-success mr-2" title="Download journal">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                                <a href="{{ $journal->editLink }}" class="btn btn-primary edit mr-2" title="Edit journal">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ $journal->deleteLink }}" class="btn btn-warning" title="Delete journal">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <a href="{{ route('user.journal.delete.all', ['user' => $user->slug]) }}" class="btn btn-danger" title="Delete all journals">
                                        <i class="fa fa-trash"></i> Delete all journals
                                    </a>
                                </div>
                                @endisset
                                @endif

                                <div class="clearfix"></div>


                                @if($user->address)
                                <div class="col-md-8 mb-4">
                                    <label>Address</label>
                                    <p>{{ $user->address->address}}</p>
                                    <p>{{ implode(',', [$user->address->city, $user->address->state, $user->address->country]) }}</p>
                                    <a class="btn btn-primary edit" href="{{ route('user.address.edit', ['user' => $user->slug, 'address' => $address]) }}">
                                        <i class="fa fa-edit"></i> Delete
                                    </a>
                                </div>
                                <div class="clearfix"></div>
                                @endif
                                <div class="col-md-8 mb-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Email</label>
                                            <p>{{ $user->email }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phone</label>
                                            <p>{{ $user->phone_number }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($user->socialMediaHandles)
                                <div class="clearfix"></div>
                                <div class="col-md-8 mb-4">
                                    <ul class="list-inline">
                                        @foreach ($user->socialMediaHandles as $socialMedia)
                                        <li>
                                            <label>{{ $socialMedia->platform }}</label>
                                            <p>{{ $socialMedia->handle }}</p>
                                            <a class="btn btn-primary edit" href="{{ route('user.socialmediahandle.edit', ['user' => $user->slug, 'socialmediahandle' => $socialMedia->slug]) }}">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <a class="btn btn-primary" href="{{ route('user.socialmediahandle.delete', ['user' => $user->slug,'socialmediahandle' => $socialMedia->slug]) }}">
                                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="my-modal"></div>

@endsection

@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@if ( 'upgrade'=='on' )
@push('js')
<script src="{{ asset('backend/js/jquery.user-upgrade.js') }}"></script>
<script>
    jQuery(function () {
        init_upgrade("{{ $route }}");
    });

</script>
@endpush
@else
@push('js')

@endpush
@endif


@push('js')
@if($charts)
@foreach ($charts as $chart)
{!! $chart->script() !!}
@endforeach
@endif
@endpush

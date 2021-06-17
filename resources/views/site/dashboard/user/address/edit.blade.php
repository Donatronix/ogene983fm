@extends('layouts.dashboard.form-custom')
@section('title')
PharmacyTherapon || Dashboard - Address }}
@endsection
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Edit Settings</h1>
            <p>Edit member address settings</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item">Address</li>
        </ul>
    </div>
    <div class="row">
        <form action="{{ route('user.address.update', ['address' => $address->slug]) }}" method="POST">
            <div class="modal-body">
                <div class="modal-product clearfix">
                    @method('PUT')
                    @csrf
                    <div class="row mb-20">
                        <div class="row">
                            <div class="col-md-6">
                                {{Form::textarea("address", old("address",$address->address),
                                        [
                                            "class" => "form-control",
                                            "placeholder" => "Address",
                                            "required"    => "required",
                                            'style'       => 'resize:none',
                                        ])
                                    }}
                                @error('address')
                                <div class="invalid-feedback text-danger" role="alert">
                                    <strong>{!! $message !!}</strong>
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <div class="col-md-12">
                                    {{Form::select('country', $countries, old('country', $address->country_id),
                                                [
                                                "id"          => "country",
                                                "class"       => "custom-select",
                                                "placeholder" => "Select Country...",
                                                'required'    => 'required'
                                                ]
                                            )
                                        }}
                                    @error('country')
                                    <div class="invalid-feedback text-danger" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    {{Form::select('state', null, old('state',$address->state_id),
                                                [
                                                "id"          => "state",
                                                "class"       => "custom-select",
                                                "placeholder" => "select state...",
                                                'required'    => 'required'
                                                ]
                                            )
                                        }}
                                    @error('state')
                                    <div class="invalid-feedback text-danger" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    {{Form::select('city', null, old('city', $address->city_id),
                                                [
                                                "id" => "city",
                                                "class" => "custom-select",
                                                "placeholder" => "select city...",
                                                'required'=>'required'
                                                ]
                                            )
                                        }}
                                    @error('city')
                                    <div class="invalid-feedback text-danger" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <input type="text" name="post_code" value="{{ old('post_code',$address->post_code) }}" placeholder="Post Code">
                                    @error('post_code')
                                    <div class="invalid-feedback text-danger" role="alert">
                                        <strong>{!! $message !!}</strong>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- .modal-product -->

            </div><!-- .modal-body -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" data-dismiss="modal">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</main>
@endsection

@push('js')
<script src="{{ asset('backend/js/jquery.address.js') }}"></script>
@endpush

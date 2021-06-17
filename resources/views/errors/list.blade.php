@php
$errors = Session::get('error');
$messages = Session::get('success');
$info = Session::get('info');
$warnings = Session::get('warning');
$status = Session::get('status');
@endphp

@if ($errors)
@if (count($errors) > 0)
@foreach($errors->all() as $value)
<div class="alert alert-danger alert-dismissible" role="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <strong>Error!</strong> {{ $value }}
</div>
@endforeach
@endif
@endif

@if ($messages)
@foreach($messages as $value)
<div class="alert alert-success alert-dismissible" role="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <strong>Success!</strong> {{ $value }}
</div>
@endforeach
@endif

@if ($status)
@foreach($status as $value)
<div class="alert alert-success alert-dismissible" role="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <strong>Success!</strong> {{ $value }}
</div>
@endforeach
@endif

@if ($info)
@foreach($info as $value)
<div class="alert alert-info alert-dismissible" role="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <strong>Info!</strong> {{ $value }}
</div>
@endforeach
@endif

@if ($warnings)
@foreach($warnings as $value)
<div class="alert alert-warning alert-dismissible" role="alert">
    <button class="close" type="button" data-dismiss="alert">×</button>
    <strong>Warning!</strong> {{ $value }}
</div>
@endforeach
@endif

@include('sweetalert::alert')

@push('css')
<link rel="stylesheet" href="{{ asset('frontend/css/jquery.sweet-modal.min.css') }}">
@endpush

@push('js')
<script src="{{ asset('frontend/js/jquery.sweet-modal.min.js') }}"></script>
<script src="{{ asset('frontend/js/helper.jquery.js') }}"></script>
<script src="{{ asset('frontend/js/helper.js') }}"></script>
@endpush

@extends('layouts.dashboard.table-basic')
@section('title')
PharmacyTherapon || Dashboard - Newsletter
@endsection
@php
use App\Helpers\Helper;$helper = new Helper;
@endphp
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-newspaper-o"></i> Newsletter</h1>
            <p>Create and send newsletters for subscribers</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item">Newsletter</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <a class="btn btn-primary pull-right" href="{{ route('newsletter.create') }}">New Newsletter</a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="row">
        @include('errors.list')
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($newsletters as $newsletter)
                                <tr>
                                    <td>{{ $newsletter->subject }}</td>
                                    <td>{!! $newsletter->publicationLabel() !!}</td>
                                    <td>
                                        <a href="{{ $newsletter->editLink }}" title="Edit" class="btn btn-primary m-1">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ $newsletter->sendLink }}" class="btn btn-warning m-1">
                                            <i class="fa fa-send"></i> Send
                                        </a>
                                        <a href="{{ $newsletter->showLink }}" class="btn btn-info m-1 preview" data-slug="{{ $newsletter->slug }}">
                                            <i class="fa fa-eye" ></i> Preview
                                        </a>
                                        <a href="{{ $newsletter->deleteLink }}" class="btn btn-danger m-1" onclick="event.preventDefault(); document.getElementById('delete-{{ $newsletter->slug }}').submit();">
                                            <i class="fa fa-trash"></i> {{ __('Delete') }}
                                        </a>
                                        <form id="delete-{{ $newsletter->slug }}" action="{{ $newsletter->deleteLink }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="err" class="text-danger"></div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="my-modal">
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Title</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <a href="button" class="btn btn-primary" id="send">
                        <i class="fa fa-send"></i> Send
                    </a>
                    <a href="button" class="btn btn-info" id="edit">
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('js')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    jQuery(function () {
        var $subject = $('.modal-title');
        var $message = $('.modal-body');
        var $sendLink = $('#send');
        var $editLink = $('#edit');

        var table = $('#sampleTable').DataTable();

        $(document).on("click", '.preview', function (e) {
            e.preventDefault();
            $('#myModal').modal('hide');
            var url = $(this).attr('href');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: "POST",
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    $("#err").fadeOut();
                },
                success: function (data) {
                    if (data.error) {
                        $("#err").html("There was an error uploading your advert!<br /> " + data.error + " <br /> If this is not the first time you are getting this message, contact <a href='{{ route('contact') }}'>Admin</a>").fadeIn();
                    } else {
                        $subject.text(data.subject);
                        $message.html(data.message);
                        $sendLink.attr('href', data.sendLink);
                        $editLink.attr('href', data.editLink);
                        $('#myModal').modal('show');
                    }
                },
                error: function (e) {
                    $("#err").html(e).fadeIn();
                }
            });
        });
        $("#err").fadeOut();
    });

</script>
@endpush

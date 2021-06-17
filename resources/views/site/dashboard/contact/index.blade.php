@extends('layouts.dashboard.index')
@section('title')
Messages
@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Contact Messages</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Contact</li>
        </ul>
    </div>
    <div class="row">
        @include('errors.list')
    </div>

    <div class="container">
        <div class="row">
            <!-- BEGIN INBOX -->
            <div class="col-md-12">
                <div class="grid email">
                    @include('errors.list')
                    <div class="grid-body">
                        <div class="row">
                            <!-- BEGIN INBOX MENU -->
                            <div class="col-md-3">
                                <h2 class="grid-title"><i class="fa fa-inbox"></i> Inbox</h2>
                                <a class="btn btn-block btn-primary" data-toggle="modal" data-target="#compose-modal"><i class="fa fa-pencil"></i>&nbsp;&nbsp;NEW MESSAGE</a>
                                <hr>
                                <div>
                                    <ul class="nav nav-pills nav-stacked">
                                        <li class="header">Folders</li>
                                        <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox (14)</a></li>
                                        <li><a href="#"><i class="fa fa-star"></i> Starred</a></li>
                                        <li><a href="#"><i class="fa fa-bookmark"></i> Important</a></li>
                                        <li><a href="#"><i class="fa fa-mail-forward"></i> Sent</a></li>
                                        <li><a href="#"><i class="fa fa-pencil-square-o"></i> Drafts</a></li>
                                        <li><a href="#"><i class="fa fa-folder"></i> Spam (217)</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- END INBOX MENU -->

                            <!-- BEGIN INBOX CONTENT -->
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label style="margin-right: 8px;" class="">
                                            <div class="icheckbox_square-blue" style="position: relative;"><input type="checkbox" id="check-all" class="icheck" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div>
                                        </label>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                Action <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a href="#">Mark as read</a></li>
                                                <li><a href="#">Mark as unread</a></li>
                                                <li><a href="#">Mark as important</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Report spam</a></li>
                                                <li><a href="#">Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-md-6 search-form">
                                        <form action="#" class="text-right">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" placeholder="Search">
                                                <span class="input-group-btn">
                                            <button type="submit" name="search" class="btn_ btn-primary btn-sm search"><i class="fa fa-search"></i></button></span>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="padding"></div>

                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                            @isset($contacts)
                                            @foreach ($contacts as $contact)
                                            <tr class="{{ $contact->status }}">
                                                <td class="action"><input type="checkbox" name="selected[]" ></td>
                                                <td class="action"><i class="fa fa-reply"></i></td>
                                                <td class="action"><i class="fa fa-bookmark-o"></i></td>
                                                <td class="name"><a href="#">{{ $contact->name }}</a></td>
                                                <td class="subject"><a href="{{ route('contact.show', ['contact' => $contact->id]) }}">{{ $contact->subject }} </a></td>
                                                <td class="time">{{ $contact->created_at->toDayDateTimeString() }}</td>
                                            </tr>
                                            @endforeach
                                            @else
                                            <tr>
                                                <td colspan="6">There are no messages.</td>
                                            </tr>

                                            @endisset
                                        </tbody>
                                    </table>
                                </div>
                                {!! $contacts->links() !!}
                            </div>
                            <!-- END INBOX CONTENT -->

                            <!-- BEGIN COMPOSE MESSAGE -->
                            <div class="modal fade" id="compose-modal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-wrapper">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-blue">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h4 class="modal-title"><i class="fa fa-envelope"></i> <span id="modal-title">Compose New Message</span></h4>
                                            </div>
                                            <form id="contact_us" action="{{ route('contact.send') }}" method="post">
                                                <div class="modal-body">
                                                    @csrf
                                                    <div class="form-group">
                                                        <input name="to" type="email" class="form-control" placeholder="To" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="cc" type="email" class="form-control" placeholder="Cc">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="bcc" type="email" class="form-control" placeholder="Bcc">
                                                    </div>
                                                    <div class="form-group">
                                                        <input name="subject" type="text" class="form-control" placeholder="Subject" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea name="message" id="email_message" class="form-control ckeditor" placeholder="Message" style="height: 120px; resize: none;"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Attachments</label>
                                                        <input type="file" name="attachment" multiple>
                                                    </div>
                                                    <div class="form-group">
                                                        <label><input type="checkbox" name="personal" value="personal"> Send using personal email address</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Discard</button>
                                                    <button type="submit" id="send" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Send Message</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END COMPOSE MESSAGE -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- END INBOX -->
        </div>
    </div>


</main>
@endsection
@push('css')
<link href="{{ asset('backend/css/email-inbox.css') }}" rel="stylesheet">
@endpush

@push('js')
<script src="{{ asset('vendor/editor/CKEDITOR.js') }}"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.reply', function () {
            var contactId = $(this).attr('data-contact');
            $.ajax({
                url: '{{ route("contact.get", ["contact" => ' + contactId + ']) }}',
                type: "POST",
                success: function (response) {
                    document.getElementById("contact_us").reset();
                    $('#to').val(response.email);
                    $('#to').attr('disabled', 'disabled');
                    $('#name').val(response.name);
                    $('#subject').val('RE:' + response.subject);
                    $('#message').val('');
                    $('#modal-title').text('Reply Message');

                    $('#compose-modal').modal('show')
                }
            });
        });

    });

</script>
@endpush

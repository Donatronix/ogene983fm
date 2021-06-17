@extends('layouts.dashboard.form-samples')
@section('title')
PharmacyTherapon || Dashboard - Newsletter
@endsection
@php
use App\Helpers\Helper;
$helper = new Helper;
@endphp
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-newspaper-o"></i> Newsletter</h1>
            <p>Edit newsletter</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('newsletter.dashboard') }}">Newsletter</a></li>
            <li class="breadcrumb-item">Edit</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <form action="{{ route('newsletter.process', ['newsletter' => $newsletter->slug]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Subject</label>
                            <input class="form-control" type="text" placeholder="Enter subject" name="subject" required value="{{ old('subject', $newsletter->subject) }}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Content</label>
                            <textarea name="message" class="form-control ckeditor" rows="4" placeholder="Enter message" style="resize: none;" required>
                            {{ old('message', $newsletter->message) }}
                            </textarea>
                            {!! $helper->e_form_error('message', $errors) !!}
                        </div>
                        <div class="form-group">
                            <label class="control-label">Upload Attachment</label>
                            <div id="preview">
                                @include('backend.newsletter.file-list', ['files' => $newsletter->getMedia('newsletter')])
                            </div>
                            <input class="form-control" type="file" name="file" id="uploadMedia" accept=".pdf,.ppt,.pptx,.doc,.docx,.xls,.xlsx" multiple>
                            {!! $helper->e_form_error('file', $errors) !!}
                        </div>
                        @honeypot
                    </div>
                    <div class="tile-footer">
                        <button class="btn btn-primary" type="submit" name="sendUpdate">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>Update and send
                        </button>
                        <button class="btn btn-warning" type="submit" name="update">
                            <i class="fa fa-plus-circle" aria-hidden="true"></i>Save draft
                        </button>

                        <a class="btn btn-danger pull-right" href="{{ route('newsletter.dashboard') }}">
                            <i class="fa fa-times-circle" aria-hidden="true"></i>Cancel
                        </a>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@push('js')
<script src="{{ asset('vendor/editor/CKEDITOR.js') }}"></script>
<script src="{{ asset('vendor/editor/adapters/jquery.js') }}"></script>
<script type="text/javascript">
    jQuery(function () {

        function previewImages() {
            var allowedExtensions = /(\.jpeg|\.png|\.jpg|\.gif|\.doc|\.docx|\.ppt|\.pptx|\.xls|\.xlsx|\.zip|\.rar|\.txt|\.pdf|\.ogg|\.mpga|\.wav|\.wmv|\.mp4|\.mov|\.mpg|\.mpeg|\.wmv|\.mkv|\.ogg|\.webm)$/i;
            if (this.files) {
                var formData = new FormData();
                formData.append('newsletter', "{{ $newsletter->slug }}");
                $.each(this.files, function (i, file) {
                    if (allowedExtensions.exec(file.name)) {
                        formData.append('file', file);

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{ route("newsletter.upload") }}',
                            type: "POST",
                            data: formData,
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: 'json',
                            beforeSend: function () {
                                //$("#preview").fadeOut();
                                $("#err").fadeOut();
                            },
                            success: function (data) {
                                var $err = $('#err').empty();
                                // var $preview = $('#preview').empty();

                                // view uploaded file.
                                $("#preview").html(data.result).fadeIn();

                                if (data.error) {
                                    $("#err").html(data.error).fadeIn();
                                }
                            },
                            error: function (e) {
                                $("#err").html(e.error);
                            }
                        });
                    }
                });

            }
        }

        $(document).on("change", '#uploadMedia', previewImages);

        $(document).on("click", '.deleteMedia', function (e) {
            e.preventDefault();
            var slug = $(this).attr('data-slug');
            var item = $(this).attr('data-file');
            var formData = new FormData();
            formData.append('slug', slug);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('newsletter.delete.media', ['newsletter'=> $newsletter->slug]) }}",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'json',
                beforeSend: function () {
                    // $("#preview").fadeOut();
                    $("#err").fadeOut();
                },
                success: function (data) {
                    // view uploaded file.
                    $("#preview").html(data).fadeIn();

                    if (data.error) {
                        $("#err").html("There was an error uploading your advert!<br /> " + data.error + " <br /> If this is not the first time you are getting this message, contact <a href='{{ route('contact') }}'>Admin</a>").fadeIn();
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

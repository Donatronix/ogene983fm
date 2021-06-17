<div id="image-preview" style="height:200px;" class="mb-40">
    {{ Form::label("$image", null,['class' => 'btn btn-success','id'=>"image-label", 'style'=>'bottom:0; margin-bottom:20px;']) }}
    {{ Form::file("$image", ['id'=>"$image",'accept' => '.jpg,.jpeg,.png', 'style'=>'display:none;'] ) }}
    @error("$image")
    <div class="invalid-feedback" role="alert">
        <strong>{!! $message !!}</strong>
    </div>
    @enderror
</div>

@push('js')
<script src="{{ asset('backend/js/jquery.uploadPreview.js') }}"></script>

<script type="text/javascript">
    jQuery(function () {
        $.uploadPreview({
            input_field: "#{{ $image }}", // Default: .image-upload
            preview_box: "#image-preview", // Default: .image-preview
            label_field: "#image-label", // Default: .image-label
            label_default: "Upload cover image", // Default: Choose File
            label_selected: "Change Image", // Default: Change File
            no_label: false // Default: false
        });
    });
</script>
@endpush

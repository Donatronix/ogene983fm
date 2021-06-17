@php
use App\Helpers\Helper;
$helper = new Helper;
@endphp


<div class="row">
    @foreach ($files as $file)
    @php
    $file=$file->getFullUrl();
    @endphp

    <div class="col-md-3">
        @if ($helper->isImage($file))
        <div class="image-container">
            <button class="btn btn-danger deleteMedia" data-slug="{{ explode('/', $directory)[1] }}" data-file="{{ $file->getFilename() }}">&times;
            </button>
            <img src="{{ $file }}" alt="" class="img-responsive equalHeight" style="width:100%;">
        </div>
        @elseif($helper->isVideo($file))
        <div class="image-container">
            <button class="btn btn-danger deleteMedia" data-slug="{{ explode('/', $directory)[1] }}" data-file="{{ $file->getFilename() }}">&times;
            </button>
            <video controls loop preload="auto" style="width: 100%;">
                <source src="{{ $file }}">
            </video>
        </div>
        @elseif($helper->isAudio($file))
        <div class="image-container">
            <button class="btn btn-danger deleteMedia" data-slug="{{ explode('/', $directory)[1] }}" data-file="{{ $file->getFilename() }}">&times;
            </button>
            <audio controls loop preload="auto" style="width: 100%;">
                <source src="{{ $file }}">
            </audio>
        </div>
        @elseif ($helper->isDocument($file))
        <div class="image-container">
            <button class="btn btn-danger deleteMedia" data-slug="{{ explode('/', $directory)[1] }}" data-file="{{ $file->getFilename() }}">&times;
            </button>
            <img src="{{ $helper->getFileIcon($file) }}" alt="" class="img-responsive equalHeight" style="width:100%;">
        </div>
        @endif
    </div>

    @endforeach
</div>

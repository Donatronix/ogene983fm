@extends('layouts.pages.blog-details')
@section('title')
{{ $title }}
@endsection
@php
use App\Helpers\Helper;
$helper = new Helper;
@endphp

@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                    @isset($breadcrumb)
                    <a href="{{ $breadcrumb['route'] }}"> {{ $breadcrumb['category'] }}</a>
                    <span>{{ $breadcrumb['title'] }}</span>
                    @else
                    <span>{{ $title }}</span>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Blog Details Section Begin -->
<section class="blog-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="blog-details-inner">
                    <div class="blog-detail-title">
                        <h2>{{ $blog->title }}</h2>
                        <p>{{ $blog->category->name ?? null }} <span>- {{ $blog->created_at->toFormattedDateString() }}</span></p>
                    </div>
                    <div class="blog-large-pic">
                        <img src="{{ $blog->cover_image }}" alt="">
                    </div>
                    <div class="blog-detail-desc">
                        {{ $blog->about }}
                    </div>
                    <div class="blog-detail-desc">
                        <div id="relative-caption">
                            <div class="row">
                                @foreach ($blog->albumUploads as $item)
                                @if ($helper->isVideo($item->item))
                                <div style="display:none;" id="video{{ $loop->index }}">
                                    <video class="lg-video-object lg-html5" controls preload="none">
                                        <source src="{{ $item->item }}" type="video/mp4">
                                        Your browser does not support HTML5 video.
                                    </video>
                                </div>
                                <a data-poster="{{ $item->cover_image }}" data-sub-html=".caption" data-html="#video{{ $loop->index }}">
                                    <div class="col-md-4">
                                        <img src="{{ $item->cover_image }}" class="img-fluid" style="width: 100%">
                                        <div class="caption text-center mt-30">
                                            <h4>{{ $item->title }}</h4>
                                            <p>{{ $item->about }}</p>
                                        </div>
                                    </div>
                                </a>
                                @elseif ($helper->isAudio($item->item))
                                <div style="display:none;" id="audio{{ $loop->index }}">
                                    <audio class="lg-video-object lg-html5" controls preload="none">
                                        <source src="{{ $item->item }}" type="audio/mp3">
                                        Your browser does not support HTML5 audio.
                                    </audio>
                                </div>
                                <a data-poster="{{ $item->cover_image }}" data-html="#audio{{ $loop->index }}">
                                    <div class="col-md-4">
                                        <img src="{{ $item->cover_image }}" class="img-fluid" style="width: 100%">
                                        <div class="caption text-center mt-30">
                                            <h4>{{ $item->title }}</h4>
                                            <p>{{ $item->about }}</p>
                                        </div>
                                    </div>
                                </a>
                                @else
                                <div class="col-md-4">
                                    <a href="{{ $item->item }}">
                                        <img src="{{ $item->item }}" class="img-fluid" style="width: 100%">
                                        <div class="caption text-center mt-30">
                                            <h4>{{ $item->title }}</h4>

                                        </div>
                                    </a>
                                </div>

                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="tag-share">
                        @isset($blog->tags)
                        <div class="details-tag">
                            <ul>
                                <li><i class="fa fa-tags"></i></li>
                                @foreach ($blog->tags as $tag)
                                <li>{{ $tag->name }}</li>
                                @endforeach

                            </ul>
                        </div>
                        @endisset
                        <div class="blog-share">
                            <span>Share:</span>
                            <div class="social-links">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>


                    <div class="blog-post">
                        <div class="row">
                            @isset($prevBlog)
                            <div class="col-lg-5 col-md-6">
                                <a href="#" class="prev-blog">
                                    <div class="pb-pic">
                                        <i class="ti-arrow-left"></i>
                                        <img src="{{ $prevBlog->cover_image }}" alt="">
                                    </div>
                                    <div class="pb-text">
                                        <span>Previous Post:</span>
                                        <h5>{{ $prevBlog->title }}</h5>
                                    </div>
                                </a>
                            </div>
                            @endisset
                            @isset($nextBlog)
                            <div class="col-lg-5 offset-lg-2 col-md-6">
                                <a href="#" class="next-blog">
                                    <div class="nb-pic">
                                        <img src="{{ $nextBlog->cover_image }}" alt="">
                                        <i class="ti-arrow-right"></i>
                                    </div>
                                    <div class="nb-text">
                                        <span>Next Post:</span>
                                        <h5>{{ $nextBlog->title }}</h5>
                                    </div>
                                </a>
                            </div>
                            @endisset
                        </div>
                    </div>
                    @if ((\Route::current()->getName() == 'post.show')||(\Route::current()->getName() == 'discussion.show'))
                    @comments(['model' => $blog])
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Section End -->
@endsection

@push('css')
<link type="text/css" rel="stylesheet" href="{{ asset('frontend/css/lightgallery.css') }}" />
<link href="{{asset('frontend/css/video-js.css')}}" rel="stylesheet">
<link href="http://vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">
@endpush

@push('js')
<script src="http://vjs.zencdn.net/4.12/video.js"></script>
<script src="{{ asset('frontend/js/video.js') }}"></script>
<!-- A jQuery plugin that adds cross-browser mouse wheel support. (Optional) -->
<script src="{{ asset('frontend/js/jquery.mousewheel.min.js') }}"></script>

<script src="{{ asset('frontend/js/lightgallery.min.js') }}"></script>

<!-- lightgallery plugins -->
<script src="{{ asset('frontend/js/lg-thumbnail.min.js') }}"></script>
<script src="{{ asset('frontend/js/lg-fullscreen.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#relative-caption').lightGallery({
            videojs: true,
            thumbnail: true,
            subHtmlSelectorRelative: true
        });

        $('#relative-caption').on('onBeforeOpen.lg', function (event, prevIndex, index) {
            $('.header-area').hide();
            $('.mobile-menu-area').hide();
        });
        $('#relative-caption').on('onCloseAfter.lg', function (event, prevIndex, index) {
            $('.header-area').show();
            $('.mobile-menu-area').show();
        });
    });

</script>
@endpush

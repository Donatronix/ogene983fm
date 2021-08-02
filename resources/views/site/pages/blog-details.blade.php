@extends('layouts.pages.blog-details')
@section('title')
{{ $title }}
@endsection

@section('content')
<!-- Breadcrumb Section Begin -->
<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-text">
                    <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                    @isset($breadcrumb)
                    @isset($breadcrumb['route'])
                    <a href="{{ $breadcrumb['route'] }}"> {{ $breadcrumb['category'] }}</a>
                    @endisset
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
                        <p>{{ $blog->category->name ?? ($blog->author ?? null) }} <span>- {{ $blog->created_at->toFormattedDateString() }}</span></p>
                    </div>
                    <div class="blog-large-pic">
                        <img src="{{ $blog->cover_image }}" alt="" style="width:50%;">
                    </div>
                    <div class="blog-detail-desc">
                        {!! $blog->content !!}
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
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $blog->url }}"><i class="fa fa-facebook"></i></a>
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ $blog->url }}"><i class="fa fa-twitter"></i></a>
                                <a href="https://wa.me/?text={{ $blog->url }}"><i class="fa fa-whatsapp"></i></a>
{{--                                <a href="{{ $blog->googlePlusShare }}"><i class="fa fa-google-plus"></i></a>--}}
{{--                                <a href="{{ $blog->instagramShare }}"><i class="fa fa-instagram"></i></a>--}}
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                                <a class="copyUrl" href="#" title="Copy Url to clipboard"><i class="fa fa-copy"></i></a>
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

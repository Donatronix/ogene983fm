@extends('layouts.pages.index')
@section('title')
Home
@endsection
@section('content')

@isset($programmes)
<!-- Hero Section Begin -->
<section class="hero-section">
    <div class="hero-items owl-carousel">
        @foreach ($programmes as $programme)
        <div class="single-hero-items set-bg" data-setbg="{{ $programme->cover_image }}">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        @php
                        $time=$programme->programmeTimes;
                        $time->each(function ($item, $key) {
                        $item->day=$item->day.'s';
                        });
                        @endphp
                        <span>{{ $time->implode('day', ', ') }}</span>
                        <h1>{{ ucwords($programme->title) }}</h1>
                        <p>{{ date('h:i a', strtotime($time->first()->from)) }} to {{ date('h:i a', strtotime($time->first()->to))}}</p>
                        <a href="{{ route('programme.show', ['programme' => $programme->slug]) }}" class="primary-btn">About</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>
<!-- Hero Section End -->
@endisset

<div class="m-5">
    <div class="row">
        <div class="col-md-8">
            @isset($songOfTheWeek)
            <!-- Deal Of The Week Section Begin-->
            <section class="deal-of-week set-bg spad" data-setbg="{{ asset($songOfTheWeek->albumArt) }}">
                <div class="container">
                    <div class="text-center col-lg-6">
                        <div class="section-title">
                            <h2>Song Of The Week</h2>
                            <h3>{{ $songOfTheWeek->title }}</h3>
                            <p>
                                {!! $songOfTheWeek->about !!}
                            </p>
                            <div class="product-price">
                                {!! $songOfTheWeek->album !!}
                                <span>/ {!! $songOfTheWeek->artist !!}</span>
                            </div>
                        </div>
                        {{-- <div class="countdown-timer" id="countdown">
                            <div class="cd-item">
                                <span>56</span>
                                <p>Days</p>
                            </div>
                            <div class="cd-item">
                                <span>12</span>
                                <p>Hrs</p>
                            </div>
                            <div class="cd-item">
                                <span>40</span>
                                <p>Mins</p>
                            </div>
                            <div class="cd-item">
                                <span>52</span>
                                <p>Secs</p>
                            </div>
                        </div>
                        <a href="#" class="primary-btn">Shop Now</a> --}}
                    </div>
                </div>
            </section>
            <!-- Deal Of The Week Section End -->
            @endisset

            @isset($categories)
            @foreach ($categories as $category)

            @if ($loop->odd )
            <!-- Women Banner Section Begin -->
            <section class="mt-4 women-banner spad">
                <div class="container-fluid">
                    <div class="row">
                        @empty ($category->category_id)
                        <div class="col-lg-3">
                            <div class="product-large set-bg" data-setbg="{{ $category->coverImage }}">
                                <h2>{{ $category->name }}</h2>
                                <a href="{{ route('post.category', ['category' => $category->slug]) }}">Discover More</a>
                            </div>
                        </div>
                        @else
                        <div class="col-lg-8 offset-lg-1">
                            <div class="filter-control">
                                <ul>
                                    @foreach ($category->subcategories as $subcat)
                                    <li @if ($loop->first) class="active" @endif>{{ $subcat->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @php
                            $categoryPosts=$category->posts->sortByDesc('updated_at');
                            @endphp
                            <div class="product-slider owl-carousel">
                                @foreach ($categoryPosts as $post)
                                <div class="product-item">
                                    <div class="pi-pic">
                                        <img src="{{ $post->coverImage }}" alt="">
                                        <div class="sale">{{ $post->category->name }}</div>
                                        <div class="icon">
                                            <i class="icon_heart_alt"></i>
                                        </div>

                                    </div>
                                    <div class="pi-text">
                                        <div class="tag-list">
                                            <div class="tag-item">
                                                <i class="fa fa-calendar-o"></i>
                                                {{ $post->updated_at->toFormattedDateString() }}
                                            </div>
                                            <div class="tag-item">
                                                <i class="fa fa-comment-o"></i>
                                                {{ $post->comments ? $post->comments->count() : 0 }}
                                            </div>
                                        </div>
                                        <a href="{{ route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]) }}">
                                            <h4>{{ $post->title }}</h4>
                                        </a>
                                        <p>
                                            {{ $post->about }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endempty
                    </div>
                </div>
            </section>
            <!-- Women Banner Section End -->

            @else

            <!-- Man Banner Section Begin -->
            <section class="man-banner spad">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="filter-control">
                                <ul>
                                    @foreach ($category->subcategories as $subcat)
                                    <li @if ($loop->first) class="active" @endif>{{ $subcat->name }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @php
                            $categoryPosts=$category->posts->sortByDesc('updated_at');
                            @endphp
                            <div class="product-slider owl-carousel">
                                @foreach ($categoryPosts as $post)
                                <div class="product-item">
                                    <div class="pi-pic">
                                        <img src="{{ $post->coverImage }}" alt="">
                                        <div class="sale">{{ $post->category->name }}</div>
                                        <div class="icon">
                                            <i class="icon_heart_alt"></i>
                                        </div>

                                    </div>
                                    <div class="pi-text">
                                        <div class="tag-list">
                                            <div class="tag-item">
                                                <i class="fa fa-calendar-o"></i>
                                                {{ $post->updated_at->toFormattedDateString() }}
                                            </div>
                                            <div class="tag-item">
                                                <i class="fa fa-comment-o"></i>
                                                {{ $post->comments ? $post->comments->count() : 0 }}
                                            </div>
                                        </div>
                                        <a href="{{ route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]) }}">
                                            <h4>{{ $post->title }}</h4>
                                        </a>
                                        <p>
                                            {{ $post->about }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-1">
                            <div class="product-large set-bg" data-setbg="{{ $category->coverImage }}">
                                <h2>{{ $category->name }}</h2>
                                <a href="{{ route('post.category', ['category' => $category->slug]) }}">Discover More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Man Banner Section End -->
            @endif
            @endforeach
            @endisset

            @isset($albums)
            <!-- Instagram Section Begin -->
            @forelse ($albums as $album)
            <div class="instagram-photo">
                <div class="insta-item set-bg" data-setbg="{{ $album->coverImage }}">
                    <div class="inside-text">
                        <i class="ti-instagram"></i>
                        <h5><a href="{{ route('gallery.album.show', ['album' => $album->slug]) }}">{{ $album->title }}</a></h5>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
            <!-- Instagram Section End -->
            @endisset


            @if($posts->count()>0)
            <!-- Latest Blog Section Begin -->
            <section class="latest-blog spad">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="section-title">
                                <h2>From The Blog</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($posts as $post)
                        <div class="col-lg-4 col-md-6">
                            <div class="single-latest-blog">
                                <a href="{{ route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]) }}">
                                    <img src="{{ $post->coverImage }}" alt="">
                                </a>
                                <div class="text-center latest-text">
                                    <div class="catagory-name">{{ $post->category->name }}</div>
                                    <div class="tag-list">
                                        <div class="tag-item">
                                            <i class="fa fa-calendar-o"></i>
                                            {{ $post->updated_at->toFormattedDateString() }}
                                        </div>
                                        <div class="tag-item">
                                            <i class="fa fa-comment-o"></i>
                                            {{ $post->comments ? $post->comments->count() : 0 }}
                                        </div>
                                    </div>
                                    <a href="{{ route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]) }}">
                                        <h4>{{ $post->title }}</h4>
                                    </a>
                                    <p>{{ $post->about }}</p>
                                    <a href="{{ route('post.show', ['category' => $post->category->slug, 'post' => $post->slug]) }}" class="primary-btn">Read more</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </section>
            <!-- Latest Blog Section End -->
            @endif
        </div>

        <div class="col-md-4">
            <a class="twitter-timeline" href="https://twitter.com/ogenefm983?ref_src=twsrc%5Etfw">Tweets by Ogene983FM</a>
            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </div>
    </div>
</div>
@endsection

@extends('layouts.pages.blog')
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
                    <span>On Air Personalities</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Section Begin -->

<!-- Blog Section Begin -->
<section class="blog-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-8 order-2 order-lg-1">
                <div class="blog-sidebar">
                    <div class="search-form">
                        <h4>Search</h4>
                        <form action="#">
                            <input type="text" placeholder="Search . . .  ">
                            <button type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div class="blog-catagory">
                        <h4>Categories</h4>
                        <ul>
                            @foreach ($categories as $category)
                            <li><a href="{{ route('post.category', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="recent-post">
                        <h4>Recent Post</h4>
                        <div class="recent-blog">
                            @foreach ($recents as $post)
                            <a href="{{ route('post.show', ['category' => $post->category->name, 'post' => $post->slug]) }}" class="rb-item">
                                <div class="rb-pic">
                                    <img src="{{ $post->coverImage }}" alt="">
                                </div>
                                <div class="rb-text">
                                    <h6>{{ $post->title }}</h6>
                                    <p>{{ $post->category->name }}<span>- {{ $post->created_at->toFormattedDateString() }}</span></p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="blog-tags">
                        <h4>Tags</h4>
                        <div class="tag-item">
                            @foreach ($tags as $tag)
                            <a href="{{ route('tag.show', ['tag' => $tag->slug]) }}">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 order-1 order-lg-2">
                <div class="row">
                    @foreach ($presenters as $presenter)
                    <div class="col-lg-6 col-sm-6">
                        <div class="blog-item">
                            <div class="bi-pic">
                                <img src="{{ $presenter->coverImage }}" alt="" style="width:371px;height:267px;">
                            </div>
                            <div class="bi-text">
                                <a href="{{ route('presenter.show', ['presenter' => $presenter->slug]) }}">
                                    <h4>{{ $presenter->name }}</h4>
                                </a>
                                @foreach ($presenter->programmes as $programme)
                                <p>
                                    <h6>{{ $programme->title}}</h6>
                                    @foreach ($programme->programmeTimes as $schedule)
                                    {{ $schedule->day }} : {{ $schedule->from }} {{ $schedule->to }}
                                    @endforeach
                                </p>
                                @endforeach

                            </div>
                        </div>
                    </div>
                    @endforeach
                    {{-- <div class="col-lg-12">
                        <div class="loading-more">
                            <i class="icon_loading"></i>
                            <a href="#">
                                Loading More
                            </a>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section End -->


@endsection

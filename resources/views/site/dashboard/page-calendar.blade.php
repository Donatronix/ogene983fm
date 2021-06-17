@extends('layouts.dashboard.page-calendar')
@section('title')

@endsection

@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-calendar"></i> Calendar</h1>
            <p>Full Calander page for managing events</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Calendar</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile row">
                <div class="col-md-3">
                    <div id="external-events">
                        <h4 class="mb-4">Draggable Events</h4>
                        <div class="fc-event">My Event 1</div>
                        <div class="fc-event">My Event 2</div>
                        <div class="fc-event">My Event 3</div>
                        <div class="fc-event">My Event 4</div>
                        <div class="fc-event">My Event 5</div>
                        <p class="animated-checkbox mt-20">
                            <label>
                                <input id="drop-remove" type="checkbox"><span class="label-text">Remove after drop</span>
                            </label>
                        </p>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@extends('layouts.dashboard.table-data-table')
@section('title')
Categories
@endsection

@php
use App\Helpers\Helper;$helper = new Helper;
@endphp
@section('content')
<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Categories</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa fa-home fa-lg"></i>
                    Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('category.dashboard') }}">Categories</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <a class="btn btn-primary icon-btn" href="{{ route('category.create') }}">
                    <i class="fa fa-plus"></i>New Category
                </a>
            </div>
        </div>
    </div>

    @include('errors.list')
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="sampleTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Parent Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><img src="{{ $category->coverImage }}" alt="" class="img-responsive" style="width:50%;"></td>
                                    <td>{{ $category->name }}</td>
                                    <td>{!! nl2br($category->about) !!}</td>
                                    <td>{{ $category->topParent ? $category->topParent->name : null }}</td>
                                    <td>
                                        <a class="btn btn-primary" href="{{ route('category.edit', ['category' => $category->slug]) }}">
                                            <i class="fa fa-edit"></i> Edit
                                        </a>
                                        <a class="btn btn-danger" href="{{ route('category.delete', ['category' => $category->slug]) }}" onclick="event.preventDefault();
                                                         document.getElementById('delete-{{ $loop->iteration }}').submit();">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                        <form id="delete-{{ $loop->iteration }}" action="{{ route('category.delete', ['category' => $category->slug]) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection


@push('js')
<!-- Data table plugin-->
<script type="text/javascript">
    jQuery(function () {
        $('#sampleTable').DataTable();
    });

</script>
@endpush

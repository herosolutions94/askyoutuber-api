@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'edit' || request()->segment(3) == 'add')
        <div class="page-body" id="dashboard">
            <div class="container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-6">
                            <h3> {{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }} Category</h3>
                        </div>
                        <div class="col-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/categories') }}"><i
                                            data-feather="bold"></i> back to categories</a></li>
                                <li class="breadcrumb-item">{{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }}
                                    Category</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form theme-form" method="post" action="" enctype="multipart/form-data"
                            id="saveForm">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success dark" role="alert">
                                    <p>{{ session('success') }}</p>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger dark" role="alert">
                                    <p>{{ session('error') }}</p>
                                </div>
                            @endif
                            @csrf


                            <div class="card">
                                <div class="card-header">
                                    <h5> Category Block</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">

                                        <div class="col">
                                            <div class="row switch-showcase">
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <div class="media">
                                                            <label class="col-form-label">Status</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->status == 1 ? 'checked' : '') : '' }}
                                                                        name="status"><span
                                                                        class="switch-state bg-primary"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="name">Name</label>
                                                        <input class="form-control" id="name" type="text"
                                                            name="name" placeholder=""
                                                            value="{{ !empty($row) ? $row->name : '' }}" required>
                                                    </div>
                                                </div>

                                            </div>



                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-pill btn-primary btn-lg" type="submit">Update Content <i
                                            class="fa fa-spinner fa-spin hidden"></i></button>
                                </div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>
    @else
        <div class="page-body" id="dashboard">
            <div class="container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-9">
                            <h3>Categories</h3>
                        </div>
                        <div class="col-3 text-right">
                            <a href="{{ url('admin/categories/add') }}" class="btn btn-primary">Add New</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <!-- Base styles-->
                    <div class="col-sm-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success dark" role="alert">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger dark" role="alert">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="advance-1">
                                        <thead>
                                            <tr>
                                                <th width="10%">Sr#</th>
                                                <th width="45%">Name</th>
                                                <th width="25%">Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($rows))
                                                @foreach ($rows as $key => $row)
                                                    <tr class="odd">
                                                        <td class="sorting_1">{{ $key + 1 }}</td>
                                                        <td>{{ $row->name }}</td>
                                                        <td>{!! getStatus($row->status) !!}</td>
                                                        <td class="action">
                                                            <a href="{{ url('admin/categories/edit/' . $row->id) }}"
                                                                class="badge badge-primary">
                                                                <i data-feather="edit"></i>
                                                            </a>
                                                            <a href="{{ url('admin/categories/delete/' . $row->id) }}"
                                                                class="badge badge-danger"
                                                                onclick="return confirm('Are you sure?');">
                                                                <i data-feather="trash-2"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="odd">
                                                    <td colspan="6">No record(s) found!</td>
                                                </tr>
                                            @endif



                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    @endif
@endsection

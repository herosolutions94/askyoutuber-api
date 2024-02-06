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
                            <h3> {{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }} Blog Post</h3>
                        </div>
                        <div class="col-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/blogs') }}"><i data-feather="bold"></i>
                                        back to Blogs</a></li>
                                <li class="breadcrumb-item">{{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }}
                                    Blog Post</li>
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
                                    <h5>Meta Details</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input class="form-control" id="meta_title" type="text" name="meta_title"
                                                    placeholder=""
                                                    value="{{ !empty($row->meta_title) ? $row->meta_title : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="site_meta_desc">Meta Description</label>
                                                <textarea class="form-control" id="meta_description" rows="3" name="meta_description">{{ !empty($row->meta_description) ? $row->meta_description : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="meta_keywords">Meta Keywords</label>
                                                <textarea class="form-control" id="meta_keywords" rows="3" name="meta_keywords">{{ !empty($row->meta_keywords) ? $row->meta_keywords : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="tags">Tags</label>
                                                <textarea class="form-control" id="tags" rows="3" name="tags">{{ !empty($row->tags) ? $row->tags : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>


                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5>Blog Block</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col">
                                            <div class="card preview">

                                                <div class="card-body">
                                                    <h5 class="card-title">Image</h5>
                                                    <img src="{{ get_site_image_src('blog', !empty($row) ? $row->image : '') }}"
                                                        class="card-img-top mb-3" alt="...">
                                                    <input class="form-control uploadFile" name="image" type="file"
                                                        data-bs-original-title="" title="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
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
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <div class="media">
                                                            <label class="col-form-label">Featured</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->featured == 1 ? 'checked' : '') : '' }}
                                                                        name="featured"><span
                                                                        class="switch-state bg-success"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <div class="media">
                                                            <label class="col-form-label">Popular</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->popular == 1 ? 'checked' : '') : '' }}
                                                                        name="popular"><span
                                                                        class="switch-state bg-warning"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="color_name">Category</label>
                                                        <select name="category" class="form-control" required>
                                                            <option value="">Select Category</option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}"
                                                                    {{ !empty($row) ? ($row->category == $category->id ? 'selected' : '') : '' }}>
                                                                    {{ !empty($category->name) ? $category->name : '' }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="title">Title</label>
                                                        <input class="form-control" id="title" type="text"
                                                            name="title" placeholder=""
                                                            value="{{ !empty($row) ? $row->title : '' }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="detail">Detail</label>
                                                        <textarea name="detail" id="detail" rows="4" class="form-control ckeditor">{{ !empty($row) ? $row->detail : '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>


                                        </div>

                                    </div>

                                </div>
                                <div class="card-footer text-end">
                                    <button class="btn btn-pill btn-primary btn-lg" type="submit">Update Content <i
                                            class="fa fa-spinner fa-spin hidden"></i></button>
                                </div>

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
                            <h3>Blog Posts</h3>
                        </div>
                        <div class="col-3 text-right">
                            <a href="{{ url('admin/blog/add') }}" class="btn btn-primary">Add New</a>
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
                                                <th width="10%">Image</th>
                                                <th width="25%">Title</th>
                                                <th width="15%">Category</th>
                                                <th width="15%">Status</th>
                                                <th width="15%">Featured</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($rows))
                                                @foreach ($rows as $key => $row)
                                                    <tr class="odd">
                                                        <td class="sorting_1">{{ $key + 1 }}</td>
                                                        <td>
                                                            <img src="{{ get_site_image_src('blog', !empty($row->image) ? $row->image : '') }}"
                                                                class="card-img-top" alt="...">
                                                        </td>
                                                        <td>{{ $row->title }}</td>
                                                        <td>{!! get_category_name($row->category) !!}</td>
                                                        <td>{!! getStatus($row->status) !!}</td>
                                                        <td>{!! getFeatured($row->featured) !!}</td>

                                                        <td class="action">
                                                            <a href="{{ url('admin/blog/edit/' . $row->id) }}"
                                                                class="badge badge-primary">
                                                                <i data-feather="edit"></i>
                                                            </a>
                                                            <a href="{{ url('admin/blog/delete/' . $row->id) }}"
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

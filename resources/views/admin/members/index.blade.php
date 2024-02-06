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
                            <h3> {{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }} Member</h3>
                        </div>
                        <div class="col-6">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/members') }}"><i
                                            data-feather="users"></i> Back to members</a></li>
                                <li class="breadcrumb-item">{{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }}
                                    Member</li>
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
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                        stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                        class="me-2">
                                        <polygon
                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                        </polygon>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li><strong>Error!</strong> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    </button>
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                        stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                        class="me-2">
                                        <polyline points="9 11 12 14 22 4"></polyline>
                                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                    </svg>
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    </button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                        stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                        class="me-2">
                                        <polygon
                                            points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                        </polygon>
                                        <line x1="15" y1="9" x2="9" y2="15"></line>
                                        <line x1="9" y1="9" x2="15" y2="15"></line>
                                    </svg>
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    </button>
                                </div>
                            @endif
                            @csrf
                            <div class="card">

                                <div class="card-header">
                                    <h5>Personal Information</h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="card preview">

                                                <div class="card-body">
                                                    <h5 class="card-title">Image</h5>
                                                    <img src="{{ get_site_image_src('members', !empty($row) ? $row->mem_image : '') }}"
                                                        class="card-img-top mb-3" alt="...">
                                                    <input class="form-control uploadFile" name="mem_image" type="file"
                                                        data-bs-original-title="" title="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_fname">First Name</label>
                                                <input class="form-control" id="mem_fname" type="text"
                                                    name="mem_fname" placeholder=""
                                                    value="{{ !empty($row->mem_fname) ? $row->mem_fname : '' }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_mname">Middle Name</label>
                                                <input class="form-control" id="mem_mname" type="text"
                                                    name="mem_mname" placeholder=""
                                                    value="{{ !empty($row->mem_mname) ? $row->mem_mname : '' }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_lname">Last Name</label>
                                                <input class="form-control" id="mem_lname" type="text"
                                                    name="mem_lname" placeholder=""
                                                    value="{{ !empty($row->mem_lname) ? $row->mem_lname : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_email">Email</label>
                                                <input class="form-control" id="mem_email" type="text"
                                                    name="mem_email" placeholder=""
                                                    value="{{ !empty($row->mem_email) ? $row->mem_email : '' }}"
                                                    {{ !empty($row) ? 'readonly' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_phone">Phone</label>
                                                <input class="form-control" id="mem_phone" type="text" name=""
                                                    placeholder=""
                                                    value="{{ !empty($row->mem_phone) ? $row->mem_phone : '' }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_password">Password</label>
                                                <input class="form-control" id="mem_password" type="text"
                                                    name="mem_password" placeholder="" value="" readonly>
                                            </div>
                                        </div> --}}
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_dob">DOB</label>
                                                <input class="form-control datepicker-here" id="mem_dob" type="text"
                                                    name="mem_dob" placeholder="" data-language="en"
                                                    value="{{ !empty($row->mem_dob) ? $row->mem_dob : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_dob">User Bio</label>
                                                <textarea class="form-control" id="mem_bio" rows="3" name="mem_bio">{{ !empty($row->mem_bio) ? $row->mem_bio : '' }}</textarea>
                                            </div>
                                        </div>

                                    </div>

                                </div>


                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5>Address Information</h5>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="mb-3">
                                                <label class="form-label" for="country">Country</label>
                                                <select name="mem_country" class="form-control" id="country" required>
                                                    <option value="">Select Country</option>
                                                    @foreach (get_countries() as $country)
                                                        <option value="{{ $country->id }}"
                                                            {{ !empty($row) ? ($row->mem_country == $country->id ? 'selected' : '') : '' }}>
                                                            {{ !empty($country->name) ? $country->name : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="mb-3">
                                                <label class="form-label" for="state">State</label>
                                                <select name="mem_state" class="form-control" id="state" required>
                                                    <option value="">Select State</option>
                                                    @if (!empty($row))
                                                        @foreach (get_country_states($row->mem_country) as $state)
                                                            <option value="{{ $state->id }}"
                                                                {{ !empty($row) ? ($row->mem_state == $state->id ? 'selected' : '') : '' }}>
                                                                {{ !empty($state->name) ? $state->name : '' }}
                                                            </option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_city">City</label>
                                                <input class="form-control" id="mem_city" type="text"
                                                    name="mem_city" placeholder=""
                                                    value="{{ !empty($row->mem_city) ? $row->mem_city : '' }}">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_zip">Zip Code / Postal Code</label>
                                                <input class="form-control" id="mem_zip" type="text" name="mem_zip"
                                                    placeholder=""
                                                    value="{{ !empty($row->mem_zip) ? $row->mem_zip : '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_address1">Address Line 1</label>
                                                <textarea class="form-control" name="mem_address1" id="mem_address1">{{ !empty($row->mem_address1) ? $row->mem_address1 : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <div>
                                                <label class="form-label" for="mem_address2">Address Line 2</label>
                                                <textarea class="form-control" name="mem_address2" id="mem_address2">{{ !empty($row->mem_address2) ? $row->mem_address2 : '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5>Status Information</h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">


                                        <div class="col-md-12">
                                            <div class="row switch-showcase">
                                                <div class="col">
                                                    <div class="mb-3">

                                                        <div class="media">
                                                            <label class="col-form-label">Status</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->mem_status == 1 ? 'checked' : '') : '' }}
                                                                        name="mem_status"><span
                                                                        class="switch-state bg-primary"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <div class="media">
                                                            <label class="col-form-label">Email Verification</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->mem_email_verified == 1 ? 'checked' : '') : '' }}
                                                                        name="mem_email_verified"><span
                                                                        class="switch-state bg-success"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="mb-3">
                                                        <div class="media">
                                                            <label class="col-form-label">Verification</label>
                                                            <div class="media-body text-end icon-state switch-outline">
                                                                <label class="switch">
                                                                    <input type="checkbox"
                                                                        {{ !empty($row) ? ($row->mem_verified == 1 ? 'checked' : '') : '' }}
                                                                        name="mem_verified"><span
                                                                        class="switch-state bg-warning"></span>
                                                                </label>
                                                            </div>
                                                        </div>
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
                <div class="row page-titles">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active"><a href="{{ url('admin/dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/members') }}">Members</a></li>
                    </ol>
                </div>

            </div>
            <!-- Container-fluid starts-->
            <div class="container-fluid">
                <div class="row">
                    <!-- Base styles-->
                    <div class="col-sm-12">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    class="me-2">
                                    <polygon
                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                    </polygon>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li><strong>Error!</strong> {{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    class="me-2">
                                    <polyline points="9 11 12 14 22 4"></polyline>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                                </svg>
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor"
                                    stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"
                                    class="me-2">
                                    <polygon
                                        points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                    </polygon>
                                    <line x1="15" y1="9" x2="9" y2="15"></line>
                                    <line x1="9" y1="9" x2="15" y2="15"></line>
                                </svg>
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                </button>
                            </div>
                        @endif
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="display dataTable" id="example">
                                        <thead>
                                            <tr>
                                                <th>Sr#</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Account Type</th>
                                                <th>Status</th>
                                                <th>Verified</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (!empty($rows))
                                                @foreach ($rows as $key => $row)
                                                    <tr class="odd">
                                                        <td class="sorting_1">{{ $key + 1 }}</td>
                                                        <td>
                                                            <img src="{{ get_site_image_src('members', !empty($row->mem_image) ? $row->mem_image : '') }}"
                                                                class="card-img-top" alt="...">
                                                        </td>
                                                        <td>{{ $row->mem_fname . ' ' . $row->mem_lname }}</td>
                                                        <td>{{ $row->mem_email }}</td>
                                                        <td>{!! userAccountType($row->googleId) !!}</td>
                                                        <td>{!! getStatus($row->mem_status) !!}</td>
                                                        <td>{!! getFeatured($row->mem_verified) !!}</td>

                                                        <td class="action">
                                                            <a href="{{ url('admin/members/edit/' . $row->id) }}"
                                                                class="badge badge-primary">
                                                                <i data-feather="edit"></i>
                                                            </a>
                                                            <a href="{{ url('admin/members/delete/' . $row->id) }}"
                                                                class="badge badge-danger"
                                                                onclick="return confirm('Are you sure?');">
                                                                <i data-feather="trash-2"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr class="odd">
                                                    <td colspan="8">No record(s) found!</td>
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

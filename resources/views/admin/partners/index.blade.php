@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    @if (request()->segment(3) == 'edit' || request()->segment(3) == 'add')
        <div class="intro-y flex items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                {{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }} Partner Item
            </h2>
        </div>
        @if (session('success'))
            <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white"> <i data-feather="alert-triangle"
                    class="w-6 h-6 mr-2"></i> <strong>Success! </strong>
                {{ session('success') }} </div>
        @endif
        @if (session('error'))
            <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white"> <i data-feather="alert-octagon"
                    class="w-6 h-6 mr-2"></i> <strong>Error!</strong>
                {{ session('error') }} </div>
        @endif


        @if (count($errors) > 0)
            <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white">
                <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><strong>Error!</strong> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="grid grid-cols-12 gap-6 mt-5">

            <div class="intro-y col-span-12 lg:col-span-6">
                <!-- BEGIN: Form Layout -->
                <form class="form theme-form" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="intro-y box p-5">
                        <div>
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt=""
                                            src="{{ get_site_image_src('partners', !empty($row) ? $row->image : '') }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Image</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="image">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label>Brand Name</label>
                            <input type="text" class="input w-full border mt-2"
                                value="{{ !empty($row) ? $row->name : '' }}" name="name" required>
                        </div>

                        <div class="mt-3">
                            <label>Active Status</label>
                            <div class="mt-2">
                                <input type="checkbox" class="input input--switch border"
                                    {{ !empty($row) ? ($row->status == 1 ? 'checked' : '') : '' }} name="status">
                            </div>
                        </div>

                        <div class="text-right mt-5">
                            <a href="{{ url('admin/partners') }}" class="button w-24 border text-gray-700 mr-1">Cancel</a>
                            <button type="submit" class="button w-24 bg-theme-1 text-white ">Save</button>
                        </div>
                    </div>
                </form>
                <!-- END: Form Layout -->
            </div>
        </div>
    @else
        <!-- Container-fluid starts-->
        <div class="intro-y flex flex-col sm:flex-row items-center mt-8">
            <h2 class="text-lg font-medium mr-auto">
                Partners
            </h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a class="button text-white bg-theme-1 shadow-md mr-2" href="{{ url('admin/partners/add') }}">Add New
                    Item</a>
            </div>
        </div>
        <div class="intro-y datatable-wrapper box p-5 mt-5">
            <?php print_r(session('success')); ?>
            @if (session('success'))
                <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-9 text-white"> <i
                        data-feather="alert-triangle" class="w-6 h-6 mr-2"></i> <strong>Success! </strong>
                    {{ session('success') }} </div>
            @endif
            @if (session('error'))
                <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white"> <i
                        data-feather="alert-octagon" class="w-6 h-6 mr-2"></i> <strong>Error!</strong>
                    {{ session('error') }} </div>
            @endif
            @csrf

            @if (count($errors) > 0)
                <div class="rounded-md flex items-center px-5 py-4 mb-2 bg-theme-6 text-white">
                    <i data-feather="alert-octagon" class="w-6 h-6 mr-2"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><strong>Error!</strong> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <table class="table table-report table-report--bordered display datatable w-full">
                <thead>
                    <tr>
                        <th class="border-b-2 whitespace-no-wrap">Sr#</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">Icon</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">Name</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">STATUS</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($rows))
                        @foreach ($rows as $key => $row)
                            <tr class="odd">
                                <td class="border-b">{{ $key + 1 }}</td>
                                <td class="text-center border-b">
                                    <div class="flex sm:justify-center">
                                        <div class="intro-x w-10 h-10 image-fit">
                                            <img alt="{{ $row->name }}" class="rounded-full"
                                                src="{{ get_site_image_src('partners', !empty($row) ? $row->image : '') }}">
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center border-b">{{ $row->name }}</td>
                                <td class="w-40 border-b">{!! getStatus($row->status) !!}</td>
                                <td class="border-b w-5">
                                    <div class="flex sm:justify-center items-center">
                                        <a class="flex items-center mr-3"
                                            href="{{ url('admin/partners/edit/' . $row->id) }}"> <i
                                                data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                        <a class="flex items-center text-theme-6"
                                            href="{{ url('admin/partners/delete/' . $row->id) }}"
                                            onclick="return confirm('Are you sure?');"> <i data-feather="trash-2"
                                                class="w-4 h-4 mr-1"></i> Delete </a>
                                    </div>

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

    @endif
@endsection

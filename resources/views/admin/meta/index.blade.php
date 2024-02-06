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
                {{ request()->segment(3) == 'edit' ? 'Edit' : 'Add New' }} Meta
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
        <div class="grid grid-cols-12 gap-3 mt-5">

            <div class="intro-y col-span-12 lg:col-span-10">
                <!-- BEGIN: Form Layout -->
                <form class="form theme-form" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    <div class="intro-y box p-5">
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Page Name</label>
                                    <input type="text" class="input w-full border mt-2"
                                        value="{{ !empty($row) ? $row->page_name : '' }}" name="page_name" required>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div class=" mt-2">
                                    <label>Page Slug</label>
                                    <select class="select2 w-full" name="slug">
                                        <option value="0">Nor Selected</option>
                                        @foreach ($all_pages as $key => $page)
                                            <option value="{{ $key }}"
                                                {{ !empty($row) && $row->slug == $key ? 'selected' : '' }}>
                                                {{ $page }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Meta Title</label>
                                    <input type="text" class="input w-full border mt-2"
                                        value="{{ !empty($row) ? $row->meta_title : '' }}" name="meta_title" required>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Meta Description</label>
                                    <textarea class="input w-full border mt-2" name="meta_description">{{ !empty($row) ? $row->meta_description : '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Meta Keywords</label>
                                    <textarea class="input w-full border mt-2" name="meta_keywords">{{ !empty($row) ? $row->meta_keywords : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Open Graph Title</label>
                                    <input type="text" class="input w-full border mt-2"
                                        value="{{ !empty($row) ? $row->og_title : '' }}" name="og_title" required>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Open Graph Description</label>
                                    <textarea class="input w-full border mt-2" name="og_description">{{ !empty($row) ? $row->og_description : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-12 xl:col-span-6">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt=""
                                            src="{{ get_site_image_src('meta', !empty($row) ? $row->image : '') }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change Meta
                                            Image</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="image" value="{{ !empty($row) ? $row->image : '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt=""
                                            src="{{ get_site_image_src('meta', !empty($row) ? $row->og_image : '') }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change Open Graph
                                            Image</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="og_image" value="{{ !empty($row) ? $row->og_image : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="text-right mt-5">
                            <a href="{{ url('admin/meta_info') }}" class="button w-24 border text-gray-700 mr-1">Cancel</a>
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
                Website Meta (SEO)
            </h2>
            <div class="w-full sm:w-auto flex mt-4 sm:mt-0">
                <a class="button text-white bg-theme-1 shadow-md mr-2" href="{{ url('admin/meta_info/add') }}">Add New
                    Page Meta</a>
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
                        <th class="border-b-2 text-center whitespace-no-wrap">Page Name</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">Page Slug</th>
                        <th class="border-b-2 text-center whitespace-no-wrap">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($rows))
                        @foreach ($rows as $key => $row)
                            <tr class="odd">
                                <td class="border-b">{{ $key + 1 }}</td>
                                <td class="text-center border-b">{{ $row->page_name }}</td>
                                <td class="text-center border-b">{{ $row->slug }}</td>
                                <td class="border-b w-5">
                                    <div class="flex sm:justify-center items-center">
                                        <a class="flex items-center mr-3"
                                            href="{{ url('admin/meta_info/edit/' . $row->id) }}"> <i
                                                data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                        <a class="flex items-center text-theme-6"
                                            href="{{ url('admin/meta_info/delete/' . $row->id) }}"
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

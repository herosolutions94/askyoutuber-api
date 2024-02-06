@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    <div class="intro-y flex items-center mt-8">
        <h2 class="text-lg font-medium mr-auto">
            Update Contact Page
        </h2>
    </div>
    <!-- Container-fluid starts-->
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-12 xxl:col-span-12">
            <form class="form theme-form" method="post" action="" enctype="multipart/form-data" id="saveForm">
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
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Meta Information
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Page Title</label>
                                    <input type="text" class="input w-full border mt-2"
                                        value="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                        name="page_title" required>
                                </div>
                                <div class="mt-3">
                                    <label>Meta Title</label>
                                    <input type="text" class="input w-full border mt-2"
                                        value="{{ !empty($sitecontent) ? $sitecontent['meta_title'] : '' }}"
                                        name="meta_title" required>
                                </div>
                                <div class="mt-3">
                                    <label>Meta Description</label>
                                    <textarea class="input w-full border mt-2" placeholder="Meta Description" name="meta_desc" required>{{ !empty($sitecontent) ? $sitecontent['meta_desc'] : '' }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <label>Meta Keywords</label>
                                    <textarea class="input w-full border mt-2" placeholder="Meta Keywords" name="meta_keyword" required>{{ !empty($sitecontent) ? $sitecontent['meta_keyword'] : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Section 1 (Banner)
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md"
                                            alt="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                            src="{{ get_site_image_src('images', !empty($sitecontent) && array_key_exists('image1', $sitecontent) ? $sitecontent['image1'] : '') }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Image</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="image1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div class="mt-3">
                                    <label>Text Block</label>
                                    <textarea data-feature="all" class="ckeditor" name="section1_detail">{{ !empty($sitecontent) ? $sitecontent['section1_detail'] : '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <!-- BEGIN: Simple Editor -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    Section 2
                                </h2>
                            </div>
                            <div class="p-5" id="simple-editor">
                                <textarea data-feature="all" class="ckeditor" name="section2_detail">{{ !empty($sitecontent) ? $sitecontent['section2_detail'] : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <!-- END: Simple Editor -->
                    <!-- BEGIN: Standard Editor -->
                    <div class="col-span-12 lg:col-span-6">
                        <div class="box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    Section 3 (Form Header)
                                </h2>
                            </div>
                            <div class="p-5" id="simple-editor">
                                <textarea data-feature="all" class="ckeditor" name="section3_detail">{{ !empty($sitecontent) ? $sitecontent['section3_detail'] : '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" class="button button--lg w-24 bg-theme-1 text-white ml-auto">Update</button>
                </div>

            </form>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    </div>
@endsection

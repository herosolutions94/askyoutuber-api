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
            Update Become a youtuber Page
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
                                            Image 1</button>
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
                                <div>
                                    <label>Link text</label>
                                    <input type="text" class="input w-full border mt-2" placeholder="Input text"
                                        value="{{ !empty($sitecontent) ? $sitecontent['section1_link_text'] : '' }}"
                                        name="section1_link_text">
                                </div>
                                <div class="mt-3">
                                    <label>Link Page</label>
                                    <select class="select2 w-full" name="section1_link_url">
                                        @foreach ($all_pages as $key => $page)
                                            <option value="{{ $key }}"
                                                {{ !empty($sitecontent['section1_link_url']) && $sitecontent['section1_link_url'] == $key ? 'selected' : '' }}>
                                                {{ $page }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-0 mt-5">
                            <div class="col-span-12 lg:col-span-12">
                                <div class="box">
                                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                        <h2 class="font-medium text-base mr-auto">
                                            Section Text
                                        </h2>
                                    </div>
                                    <div class="p-5" id="simple-editor">
                                        <textarea data-feature="all" class="ckeditor" name="section2_text">{{ !empty($sitecontent) && !empty($sitecontent['section2_text']) ? $sitecontent['section2_text'] : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- BEGIN: Simple Editor -->
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="p-5" id="simple-editor">
                                        <input type="text" class="input w-full border mt-2"
                                            placeholder="Counter 1 Number"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_number1']) ? $sitecontent['section2_counter_number1'] : '' }}"
                                            name="section2_counter_number1">
                                        <input type="text" class="input w-full border mt-2" placeholder="Counter 1 Text"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_text1']) ? $sitecontent['section2_counter_text1'] : '' }}"
                                            name="section2_counter_text1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="p-5" id="simple-editor">
                                        <input type="text" class="input w-full border mt-2"
                                            placeholder="Counter 2 Number"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_number2']) ? $sitecontent['section2_counter_number2'] : '' }}"
                                            name="section2_counter_number2">
                                        <input type="text" class="input w-full border mt-2"
                                            placeholder="Counter 2 Text"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_text2']) ? $sitecontent['section2_counter_text2'] : '' }}"
                                            name="section2_counter_text2">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="p-5" id="simple-editor">
                                        <input type="text" class="input w-full border mt-2"
                                            placeholder="Counter 3 Number"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_number3']) ? $sitecontent['section2_counter_number3'] : '' }}"
                                            name="section2_counter_number3">
                                        <input type="text" class="input w-full border mt-2"
                                            placeholder="Counter 3 Text"
                                            value="{{ !empty($sitecontent) && !empty($sitecontent['section2_counter_text3']) ? $sitecontent['section2_counter_text3'] : '' }}"
                                            name="section2_counter_text3">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <!-- BEGIN: Simple Editor -->
                    <div class="col-span-12 lg:col-span-12">
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
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Section 3 (How it works)
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-12">
                                <div>
                                    <label>Text Block</label>
                                    <textarea data-feature="all" class="ckeditor" name="section3_detail">{{ !empty($sitecontent) ? $sitecontent['section3_detail'] : '' }}</textarea>
                                </div>


                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-6 mt-5">
                            <!-- BEGIN: Simple Editor -->
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                        <h2 class="font-medium text-base mr-auto">
                                            Step 1(Create Profile)
                                        </h2>
                                    </div>
                                    <div class="p-5" id="simple-editor">
                                        <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image mb-5">
                                            <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                                <img class="rounded-md"
                                                    alt="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                                    src="{{ get_site_image_src('images', !empty($sitecontent) && array_key_exists('image2', $sitecontent) ? $sitecontent['image2'] : '') }}">
                                            </div>
                                            <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                                <button type="button" class="button w-full bg-theme-1 text-white">Change
                                                    Step 1 Image</button>
                                                <input type="file"
                                                    class="w-full h-full top-0 left-0 absolute opacity-0" name="image2">
                                            </div>
                                        </div>
                                        <textarea data-feature="all" class="ckeditor" name="section3_step1">{{ !empty($sitecontent) ? $sitecontent['section3_step1'] : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                        <h2 class="font-medium text-base mr-auto">
                                            Step 2(Get questions and answered them)
                                        </h2>
                                    </div>
                                    <div class="p-5" id="simple-editor">
                                        <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image mb-5">
                                            <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                                <img class="rounded-md"
                                                    alt="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                                    src="{{ get_site_image_src('images', !empty($sitecontent) && array_key_exists('image3', $sitecontent) ? $sitecontent['image3'] : '') }}">
                                            </div>
                                            <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                                <button type="button" class="button w-full bg-theme-1 text-white">Change
                                                    Step 1 Image</button>
                                                <input type="file"
                                                    class="w-full h-full top-0 left-0 absolute opacity-0" name="image3">
                                            </div>
                                        </div>
                                        <textarea data-feature="all" class="ckeditor" name="section3_step2">{{ !empty($sitecontent) ? $sitecontent['section3_step2'] : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <div class="box">
                                    <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                        <h2 class="font-medium text-base mr-auto">
                                            Step 3(Get Paid)
                                        </h2>
                                    </div>
                                    <div class="p-5" id="simple-editor">
                                        <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image mb-5">
                                            <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                                <img class="rounded-md"
                                                    alt="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                                    src="{{ get_site_image_src('images', !empty($sitecontent) && array_key_exists('image4', $sitecontent) ? $sitecontent['image4'] : '') }}">
                                            </div>
                                            <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                                <button type="button" class="button w-full bg-theme-1 text-white">Change
                                                    Step 1 Image</button>
                                                <input type="file"
                                                    class="w-full h-full top-0 left-0 absolute opacity-0" name="image4">
                                            </div>
                                        </div>
                                        <textarea data-feature="all" class="ckeditor" name="section3_step3">{{ !empty($sitecontent) ? $sitecontent['section3_step3'] : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <!-- BEGIN: Simple Editor -->
                    <div class="col-span-12 lg:col-span-12">
                        <div class="box">
                            <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200">
                                <h2 class="font-medium text-base mr-auto">
                                    Section 4 (Red Background strip)
                                </h2>
                            </div>
                            <div class="p-5" id="simple-editor">
                                <textarea data-feature="all" class="ckeditor" name="section4_detail">{{ !empty($sitecontent) ? $sitecontent['section4_detail'] : '' }}</textarea>
                            </div>
                            <div class="grid grid-cols-12 gap-2 p-5">
                                <div class="col-span-12 xl:col-span-6">
                                    <div>
                                        <label>Link text</label>
                                        <input type="text" class="input w-full border mt-2" placeholder="Input text"
                                            value="{{ !empty($sitecontent) && array_key_exists('section4_link_text', $sitecontent) ? $sitecontent['section4_link_text'] : '' }}"
                                            name="section4_link_text">
                                    </div>
                                </div>
                                <div class="col-span-12 xl:col-span-6">
                                    <div class="mt-3">
                                        <label>Link Page</label>
                                        <select class="select2 w-full" name="section4_link_url">
                                            @foreach ($all_pages as $key => $page)
                                                <option value="{{ $key }}"
                                                    {{ !empty($sitecontent['section4_link_url']) && $sitecontent['section4_link_url'] == $key ? 'selected' : '' }}>
                                                    {{ $page }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Section 5
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md"
                                            alt="{{ !empty($sitecontent) ? $sitecontent['page_title'] : '' }}"
                                            src="{{ get_site_image_src('images', !empty($sitecontent) && array_key_exists('image5', $sitecontent) ? $sitecontent['image5'] : '') }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Image </button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="image5">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div class="mt-3">
                                    <label>Text Block</label>
                                    <textarea data-feature="all" class="ckeditor" name="section5_detail">{{ !empty($sitecontent) ? $sitecontent['section5_detail'] : '' }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <label>Youtube Video Url</label>
                                    <textarea name="section5_video_iframe" CLASS="input w-full border mt-2">{{ !empty($sitecontent) ? $sitecontent['section5_video_iframe'] : '' }}</textarea>
                                </div>
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

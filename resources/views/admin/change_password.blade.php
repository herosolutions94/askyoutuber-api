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
            Update Passowrd
        </h2>
    </div>
    <!-- Container-fluid starts-->
    <div class="grid grid-cols-12 gap-6">
        <!-- BEGIN: Profile Menu -->
        <div class="col-span-12 lg:col-span-4 xxl:col-span-3 flex lg:block flex-col-reverse">
            <div class="intro-y box mt-5">
                <div class="relative flex items-center p-5">
                    <div class="w-12 h-12 image-fit">
                        <img alt="Midone Tailwind HTML Admin Template" class="rounded-full"
                            src="{{ get_site_image_src('images', $site_settings->site_logo) }}">
                    </div>
                    <div class="ml-4 mr-auto">
                        <div class="font-medium text-base">{{ $site_settings->site_name }}</div>
                    </div>
                </div>
                <div class="p-5 border-t border-gray-200">
                    <a class="flex items-center mt-5" href="{{ url('admin/site_settings') }}"> <i data-feather="box"
                            class="w-4 h-4 mr-2"></i>
                        Site Settings </a>
                    <a class="flex items-center mt-5 text-theme-1 font-medium" href="{{ url('admin/change-password') }}"> <i
                            data-feather="lock" class="w-4 h-4 mr-2"></i>
                        Change Password </a>
                </div>

            </div>
        </div>
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <!-- END: Profile Menu -->
            <form class="form theme-form" method="post" action="" enctype="multipart/form-data">
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
                            Change Password
                        </h2>
                    </div>
                    <div class="p-5">
                        <div>
                            <label>Current Password</label>
                            <input type="password"
                                class="input w-full border mt-2  @error('current_password') is-invalid @enderror"
                                name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label>New Password</label>
                            <input type="password"
                                class="input w-full border mt-2  @error('new_password') is-invalid @enderror"
                                name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label>Confirm Password</label>
                            <input type="password"
                                class="input w-full border mt-2  @error('confirm_password') is-invalid @enderror"
                                name="confirm_password">
                            @error('confirm_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="button bg-theme-1 text-white mt-4">Change Password</button>
                    </div>
                </div>




            </form>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

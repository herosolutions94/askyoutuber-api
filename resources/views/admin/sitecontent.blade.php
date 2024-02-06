@extends('layouts.adminlayout')
@section('page_meta')
    <meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
    <meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
    <meta name="author" content="{{ !empty($site_settings->site_name) ? $site_settings->site_name : 'Login' }}">
    <title>Admin - {{ $site_settings->site_name }}</title>
@endsection
@section('page_content')
    <div>
        <h2 class="intro-y text-lg font-medium mt-10">
            Website Pages
        </h2>
        <span><code>All Website pages are listed here. Click on any page to change the
                content.</code></span>
        <!-- Container-fluid starts-->
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                <table class="table table-report -mt-2">
                    <thead>
                        <tr>
                            <th class="whitespace-no-wrap">Sr#</th>
                            <th class="whitespace-no-wrap">Page Name</th>
                            <th class=" whitespace-no-wrap">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">1</div>
                            </td>
                            <td class="">Home</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/home') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">2</div>
                            </td>
                            <td class="">About</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/about') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">3</div>
                            </td>
                            <td class="">Become a youtuber</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/become_a_youtuber') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">4</div>
                            </td>
                            <td class="">Help</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/help') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">5</div>
                            </td>
                            <td class="">Contact</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/contact') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">6</div>
                            </td>
                            <td class="">Sign In</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/signin') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">7</div>
                            </td>
                            <td class="">Sign Up</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/signup') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">8</div>
                            </td>
                            <td class="">Forgot Password</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/forgot') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">9</div>
                            </td>
                            <td class="">Reset Password</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/reset') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">10</div>
                            </td>
                            <td class="">Privacy Policy</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/privacy_policy') }}"> <i
                                            data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">11</div>
                            </td>
                            <td class="">Terms & Conditions</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/terms_conditions') }}">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                        <tr class="intro-x">
                            <td>
                                <div class="text-gray-600 text-xs whitespace-no-wrap">12</div>
                            </td>
                            <td class="">Search</td>
                            <td class="table-report__action w-56">
                                <div class="flex items-center">
                                    <a class="flex items-center mr-3" href="{{ url('admin/pages/search') }}">
                                        <i data-feather="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    @endsection

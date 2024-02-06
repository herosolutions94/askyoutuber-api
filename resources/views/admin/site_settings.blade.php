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
            Update Website Settings <?php print_r(session('success')); ?>
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
                    <a class="flex items-center mt-5 text-theme-1 font-medium" href="{{ url('admin/site_settings') }}"> <i
                            data-feather="box" class="w-4 h-4 mr-2"></i>
                        Site Settings </a>
                    <a class="flex items-center mt-5" href="{{ url('admin/change-password') }}"> <i data-feather="lock"
                            class="w-4 h-4 mr-2"></i>
                        Change Password </a>
                </div>

            </div>
        </div>
        <div class="col-span-12 lg:col-span-8 xxl:col-span-9">
            <!-- END: Profile Menu -->
            <form class="form theme-form" method="post" action="{{ url('admin/settings') }}" enctype="multipart/form-data">
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
                            Display Information
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt="{{ $site_settings->site_name }}"
                                            src="{{ get_site_image_src('images', $site_settings->site_logo) }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Logo</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="site_logo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div>
                                    <label>Site Name</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder="Input text"
                                        value="{{ $site_settings->site_name }}" name="site_name">
                                </div>
                                <div class="mt-3">
                                    <label>Email</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder="Input text"
                                        value="{{ $site_settings->site_email }}" name="site_email">
                                </div>
                                <div class="mt-3">
                                    <label>Phone</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder="Input text"
                                        value="{{ $site_settings->site_phone }}" name="site_phone">
                                </div>
                                <div class="mt-3">
                                    <label>Address</label>
                                    <textarea class="input w-full border mt-2" placeholder="Adress" name="site_address">{{ $site_settings->site_address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Meta Information
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt="{{ $site_settings->site_name }}"
                                            src="{{ get_site_image_src('images', $site_settings->site_thumb) }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Thumbnail</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="site_thumb">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div>
                                    <label>Meta Description</label>
                                    <textarea class="input w-full border mt-2" placeholder="Meta Description" name="site_meta_desc">{{ $site_settings->site_meta_desc }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <label>Meta Keywords</label>
                                    <textarea class="input w-full border mt-2" placeholder="Meta Keywords" name="site_meta_keyword">{{ $site_settings->site_meta_keyword }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            General Information
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-4">
                                <div class="border border-gray-200 rounded-md p-5 change_thumbnail_image">
                                    <div class="w-40 h-40 relative image-fit cursor-pointer zoom-in mx-auto">
                                        <img class="rounded-md" alt="{{ $site_settings->site_name }}"
                                            src="{{ get_site_image_src('images', $site_settings->site_icon) }}">
                                    </div>
                                    <div class="w-40 mx-auto cursor-pointer relative mt-5">
                                        <button type="button" class="button w-full bg-theme-1 text-white">Change
                                            Fav Icon</button>
                                        <input type="file" class="w-full h-full top-0 left-0 absolute opacity-0"
                                            name="site_icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-8">
                                <div>
                                    <label>Site Domain</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder="Input text"
                                        value="{{ $site_settings->site_domain }}" name="site_domain">
                                </div>
                                <div class="mt-3">
                                    <label>Site No-Reply Email</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder="Input text"
                                        value="{{ $site_settings->site_noreply_email }}" name="site_noreply_email">
                                </div>
                                <div class="mt-3">
                                    <label>Copyright</label>
                                    <textarea class="input w-full border mt-2" placeholder="Copyright" name="site_copyright">{{ $site_settings->site_copyright }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <label>About</label>
                                    <textarea class="input w-full border mt-2" placeholder="About" name="site_about">{{ $site_settings->site_about }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Stripe Information
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Testing Mode</label>
                                    <div class="mt-2">
                                        <select class="select2 w-full" name="site_stripe_type">
                                            <option value="1"
                                                {{ !empty($site_settings) ? ($site_settings->site_stripe_type == 1 ? 'selected' : '') : '' }}>
                                                Yes</option>
                                            <option value="0"
                                                {{ !empty($site_settings) ? ($site_settings->site_stripe_type == 0 ? 'selected' : '') : '' }}>
                                                No</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label>Testing Api Key</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_stripe_testing_api_key }}"
                                        name="site_stripe_testing_api_key">
                                </div>
                                <div>
                                    <label>Testing Secret Key</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_stripe_testing_api_key }}"
                                        name="site_stripe_testing_api_key">
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Live API Key</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_stripe_live_api_key }}"
                                        name="site_stripe_live_api_key">
                                </div>
                                <div class="mt-3">
                                    <label>Live Secret Key</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_stripe_live_secret_key }}"
                                        name="site_stripe_live_secret_key">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="intro-y box lg:mt-5">
                    <div class="flex items-center p-5 border-b border-gray-200">
                        <h2 class="font-medium text-base mr-auto">
                            Social Links
                        </h2>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Instagram</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_instagram }}" name="site_instagram">
                                </div>
                                <div class="mt-3">
                                    <label>Facebook</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_facebook }}" name="site_facebook">
                                </div>
                                <div class="mt-3">
                                    <label>Linkedin</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_linkedin }}" name="site_linkedin">
                                </div>
                            </div>
                            <div class="col-span-12 xl:col-span-6">
                                <div>
                                    <label>Pinterest</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_pinterest }}" name="site_pinterest">
                                </div>
                                <div class="mt-3">
                                    <label>Twitter</label>
                                    <input type="text" class="input w-full border   mt-2" placeholder=""
                                        value="{{ $site_settings->site_twitter }}" name="site_twitter">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button type="submit" class="button w-20 bg-theme-1 text-white ml-auto">Update</button>
                        </div>
                    </div>
                </div>



            </form>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

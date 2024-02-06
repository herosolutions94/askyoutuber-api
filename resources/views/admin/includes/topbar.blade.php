<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <div class="-intro-x breadcrumb mr-auto hidden sm:flex"> <a href="" class="breadcrumb--active">Dashboard</a>
    </div>
    <!-- END: Breadcrumb -->
    <!-- BEGIN: Account Menu -->
    <div class="relative flex gap-5">
        <div class="dropdown relative"> <button
                class="dropdown-toggle button inline-block bg-theme-7 text-white">{{ Session()->has('site_lang') && session('site_lang') == 'en' ? 'English' : 'Spanish' }}</button>
            <div class="dropdown-box mt-10 absolute w-40 top-0 left-0 z-20">
                <div class="dropdown-box__content box p-2">
                    <a href="{{ url('admin/change_language') }}"
                        class="block p-2 transition duration-300 ease-in-out bg-white hover:bg-gray-200 rounded-md">{{ Session()->has('site_lang') && session('site_lang') == 'en' ? 'Spanish' : 'English' }}</a>
                </div>
            </div>
        </div>
        <a href="{{ url('admin/logout') }}"
            class="button w-32 mr-2 mb-2 flex items-center justify-center bg-theme-1 text-white">
            <i data-feather="toggle-right" class="w-4 h-4 mr-2"></i> Logout</a>
    </div>
    <!-- END: Account Menu -->
</div>

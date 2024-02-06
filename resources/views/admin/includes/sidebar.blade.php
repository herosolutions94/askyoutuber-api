<?php $admin_page = request()->segment(2); ?>

<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4 sidebar-logo">
        <img alt="{{ $site_settings->site_name }}" class="w-6"
            src="{{ get_site_image_src('images', $site_settings->site_logo) }}">
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{ url('admin/dashboard') }}"
                class="side-menu  {{ $admin_page == 'dashboard' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>

        <li class="side-nav__devider my-6"></li>
        <li class=" ">
            <a class="side-menu {{ $admin_page == 'members' ? 'side-menu--active' : '' }}"
                href="{{ url('admin/members') }}">
                <div class="side-menu__icon"> <i data-feather="users"></i> </div>
                <div class="side-menu__title"> Members </div>
            </a>
        </li>
        <li class=" ">
            <a class="side-menu {{ $admin_page == 'contact' ? 'side-menu--active' : '' }}"
                href="{{ url('admin/contact') }}">
                <div class="side-menu__icon"> <i data-feather="message-square"></i> </div>
                <div class="side-menu__title"> Contact </div>
            </a>
        </li>
        <li class=" ">
            <a class="side-menu {{ $admin_page == 'subscribers' ? 'side-menu--active' : '' }}"
                href="{{ url('admin/subscribers') }}">
                <div class="side-menu__icon"> <i data-feather="mail"></i> </div>
                <div class="side-menu__title"> Subscribers </div>
            </a>
        </li>
        <li class="side-nav__devider my-6"></li>

        <li>
            <a href="javascript:void(0)"
                class="side-menu {{ $admin_page == 'sitecontent' || $admin_page == 'pages' || $admin_page == 'categories' || $admin_page == 'testimonials' || $admin_page == 'partners' || $admin_page == 'faqs' || $admin_page == 'faq_categories' || $admin_page == 'meta_info' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="edit"></i> </div>
                <div class="side-menu__title"> Website CMS <i data-feather="chevron-down" class="menu__sub-icon"></i>
                </div>
            </a>
            <ul
                class=" {{ $admin_page == 'sitecontent' || $admin_page == 'pages' || $admin_page == 'categories' || $admin_page == 'testimonials' || $admin_page == 'partners' || $admin_page == 'faqs' || $admin_page == 'faq_categories' || $admin_page == 'meta_info' ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ url('admin/sitecontent') }}"
                        class="side-menu {{ $admin_page == 'sitecontent' || $admin_page == 'pages' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="layout"></i> </div>
                        <div class="side-menu__title"> Website Pages </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/meta_info') }}"
                        class="side-menu {{ $admin_page == 'meta_info' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="globe"></i> </div>
                        <div class="side-menu__title"> Website Meta </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/categories') }}"
                        class="side-menu {{ $admin_page == 'categories' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="layers"></i> </div>
                        <div class="side-menu__title"> Categories </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/testimonials') }}"
                        class="side-menu {{ $admin_page == 'testimonials' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="smile"></i> </div>
                        <div class="side-menu__title"> Testimonials </div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('admin/partners') }}"
                        class="side-menu {{ $admin_page == 'partners' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="radio"></i> </div>
                        <div class="side-menu__title"> Partners </div>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)"
                        class="side-menu {{ $admin_page == 'faqs' || $admin_page == 'faq_categories' ? 'side-menu--active has_sub_menu' : '' }}">
                        <div class="side-menu__icon"> <i data-feather="help-circle"></i> </div>
                        <div class="side-menu__title"> Help <i data-feather="chevron-down" class="menu__sub-icon"></i>
                        </div>
                    </a>
                    <ul
                        class=" {{ $admin_page == 'faqs' || $admin_page == 'faq_categories' ? 'side-menu__sub-open' : '' }}">
                        <li>
                            <a href="{{ url('admin/faq_categories') }}"
                                class="side-menu {{ $admin_page == 'faq_categories' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="list"></i> </div>
                                <div class="side-menu__title"> FAQ Categories </div>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('admin/faqs') }}"
                                class="side-menu {{ $admin_page == 'faqs' ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon"> <i data-feather="help-circle"></i> </div>
                                <div class="side-menu__title"> FAQs </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>
        <li class="side-nav__devider my-6"></li>
        <li>
            <a href="{{ url('admin/site_settings') }}"
                class="side-menu {{ $admin_page == 'site_settings' || $admin_page == 'change-password' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-feather="trello"></i> </div>
                <div class="side-menu__title"> Profile
                </div>
            </a>
        </li>


    </ul>
</nav>

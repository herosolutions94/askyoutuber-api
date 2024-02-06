<meta name="description" content={{ !empty($site_settings) ? $site_settings->site_meta_desc : '' }}">
<meta name="keywords" content="{{ !empty($site_settings) ? $site_settings->site_meta_keyword : '' }}">
<meta name="author" content="pixelstrap">
<link rel="icon"
    href="{{ !empty($site_settings) ? get_site_image_src('images', $site_settings->site_icon) : get_site_image_src('images', '') }}"
    type="image/x-icon">
<link rel="shortcut icon"
    href="{{ !empty($site_settings) ? get_site_image_src('images', $site_settings->site_icon) : get_site_image_src('images', '') }}"
    type="image/x-icon">
<link rel="stylesheet" href="{{ asset('admin/dist/css/app.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('admin/css/custom.css') }}">

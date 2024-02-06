<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @include('admin/includes/master')

    @yield('page_meta')

</head>

<body class="app">
    <div class="flex">
        @include('admin/includes/sidebar')
        <div class="content">
            @include('admin/includes/topbar')
            @yield('page_content')
        </div>
    </div>

    @include('admin/includes/footer')

</body>

</html>

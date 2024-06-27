{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scrub - @yield('title')</title>

    @include('admin.layouts.styles')

    @yield('css')
</head>
<body >
    <div id="layout-wrapper">
        @include('admin.layouts.extras')
        @include('admin.layouts.sidebar')
        @include('admin.layouts.header')

        <div class="content">
            @yield('content')
        </div>
    </div>

    @include('admin.layouts.scripts')

    @if(session()->has('success'))
        <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
    @endif
    @if(session()->has('error'))
        <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
    @endif
    @yield('js')

</body>
</html> --}}



<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NASA - @yield('title')</title>

    <!-- Favicon -->
    {{-- <link rel="shortcut icon" href="../../assets/images/fav-ic-n.png" /> --}}
    @include('admin.layouts.styles')

    @yield('css')


</head>

<body>

    <!-- preloader -->
    <div class="preloader">
        <img src="{{ asset('admin/logo.jpg')}}" alt="logo">
        <div class="preloader-icon"></div>
    </div>
    <!-- ./ preloader -->

    @include('admin.layouts.extras')
    @include('admin.layouts.sidebar')


    <!-- layout-wrapper -->
    <div class="layout-wrapper">

        @include('admin.layouts.header')
      

        <!-- content -->
        <div class="content ">

            @yield('content')

        </div>
        <!-- ./ content -->

    </div>
    <!-- ./ layout-wrapper -->

    @include('admin.layouts.scripts')

    @if(session()->has('success'))
        <script type="text/javascript">  toastr.success('{{ session('success')}}');</script>
    @endif
    @if(session()->has('error'))
        <script type="text/javascript"> toastr.error('{{ session('error')}}');</script>
    @endif
    @yield('js')
</body>

</html>
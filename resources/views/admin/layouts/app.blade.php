<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NASA - @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    {{-- <link rel="shortcut icon" href="../../assets/images/fav-ic-n.png" /> --}}
    @include('admin.layouts.styles')
    <style>
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

         .notification {
            position: relative;
        }

        .icon {
            cursor: pointer;
            position: relative;
        }

        .my-badge {
            position: absolute;
            top: 0;
            right: 0;
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 50%;
            text-transform: uppercase;
            line-height: 0.75;
            display: inline-block;
            font-size: 0.8125em;
            font-weight: 500;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
        }

        .notification_dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .notification_dropdown ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .notification_dropdown li {
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .notification_dropdown li:last-child {
            border-bottom: none;
        }

        .notification_dropdown.active {
            display: block;
        } 

    </style>
    @yield('css')

</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('admin.layouts.sidebar')
            <div class="layout-page">
                @include('admin.layouts.header')
                <div class="content-wrapper">
                    @yield('content')
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
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

</html>
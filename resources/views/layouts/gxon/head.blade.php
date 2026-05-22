
<head>
    <base href="../">
    <!-- begin::GXON Meta Basic -->
    <meta charset="utf-8">
    <meta name="theme-color" content="#316AFF">
    <meta name="robots" content="index, follow">
    <meta name="author" content="LayoutDrop">
    <meta name="format-detection" content="telephone=no">
    <meta name="description" content="GXON is a professional and modern HR Management Admin Dashboard Template built with Bootstrap.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- end::GXON Meta Basic -->

    <!-- begin::GXON Website Page Title -->
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <!-- end::GXON Website Page Title -->

    <!-- begin::GXON Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end::GXON Mobile Specific -->

    <!-- begin::GXON Favicon Tags -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <!-- end::GXON Favicon Tags -->

    <!-- begin::GXON Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- end::GXON Google Fonts -->

    <!-- begin::GXON Required Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <!-- end::GXON Required Stylesheet -->

    <!-- begin::GXON CSS Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
    <!-- end::GXON CSS Stylesheet -->

    @stack('styles')
</head>
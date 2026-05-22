<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('layouts.gxon.head')

<body>
    <div class="page-layout">

        <!-- begin::GXON Page Header (NAVBAR) -->
        @include('layouts.gxon.navbar')
        <!-- end::GXON Page Header -->

        <!-- begin::GXON Sidebar Menu -->
        @include('layouts.gxon.sidebar')
        <!-- end::GXON Sidebar Menu -->

        <main class="app-wrapper">

            <div class="container">

                <!-- Content Header (Breadcrumb/Title) -->
                <div class="app-page-head">
                    @yield('content-header')
                </div>

                <!-- Main content -->
                @yield('content')
                <!-- /.content -->

            </div>

        </main>

        <!-- begin::GXON Footer -->
        @include('layouts.gxon.footer')
        <!-- end::GXON Footer -->

    </div>

    <!-- begin::GXON Page Scripts -->
    <!-- Anda harus memastikan semua aset JS ini ada di public/assets/libs/ dan public/assets/js/ -->
    <script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/js/appSettings.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- end::GXON Page Scripts -->
    
    @stack('scripts')
</body>
</html>
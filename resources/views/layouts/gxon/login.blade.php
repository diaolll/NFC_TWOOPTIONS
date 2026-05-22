<!DOCTYPE html>
<html lang="en">

<head>

  <base href="../">

  <!-- begin::GXON Meta Basic -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Login') | {{ config('app.name', 'Laravel') }}</title>
  <!-- end::GXON Meta Basic -->

  <!-- begin::GXON Favicon Tags -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
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

  <style>
    .auth-frame {
      background: transparent !important;
    }
    .auth-frame img {
      display: block !important;
    }
  </style>

</head>

<body>
  <div class="page-layout">

    <div class="auth-frame-wrapper">
      <div class="row g-0 h-100">
        <div class="col-lg-6" style="background: url('{{ asset('assets/images/4dddc40a4bfd08371d0ad039cfd87018.jpg') }}') center/cover no-repeat;">
        </div>
        <div class="col-lg-6 align-self-center">
          <div class="p-4 p-sm-5 maxw-450px m-auto">
            <div class="mb-4 text-center">
              <a href="{{ url('/') }}" aria-label="Logo">
                <img class="visible-light" src="{{ asset('assets/images/logo-full.svg') }}" alt="Logo">
                <img class="visible-dark" src="{{ asset('assets/images/logo-full-white.svg') }}" alt="Logo">
              </a>
            </div>
            @yield('content')

          </div>
        </div>
      </div>
    </div>

  </div>
  <!-- begin::GXON Page Scripts -->
  <script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
  <script src="{{ asset('assets/js/appSettings.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <!-- end::GXON Page Scripts -->

  @stack('scripts')
</body>

</html>

{{-- resources/views/yourmodule/index.blade.php --}}
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Halaman Index - Modul</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('srtdash/assets/images/icon/favicon.ico') }}">
    
    <!-- CSS Assets -->
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/metisMenu.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('srtdash/assets/css/responsive.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">


   
</head>

<body>
    <div id="preloader">
        <div class="loader"></div>
    </div>

    <div class="page-container">
        @include('layout.sidebar') <!-- Sidebar -->
        <div class="main-content">
            @include('layout.header') <!-- Header -->
            @include('layout.breadcrumb') <!-- Breadcrumb -->

            <div class="main-content-inner">
                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="card">
                            <div class="card-body">
                                @yield('content') <!-- Content -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layout.footer') <!-- Optional: Footer -->
    </div>


    <!-- JS Assets -->
    <script src="{{ asset('srtdash/assets/js/vendor/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('srtdash/assets/js/jquery.slicknav.min.js') }}"></script>

    {{-- Sweetalert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>



    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('srtdash/assets/js/scripts.js') }}"></script>
    @stack('js')
</body>

</html>

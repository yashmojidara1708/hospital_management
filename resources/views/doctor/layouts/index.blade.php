@php
    $currentRouteName = \Route::currentRouteName();
    $DoctorData = session('doctors_data');
    $DoctorRole = isset($DoctorData['role']) ? $DoctorData['role'] : '';
    $DoctorName = isset($DoctorData['name']) ? $DoctorData['name'] : '';
    $DoctorEmail = isset($DoctorData['email']) ? $DoctorData['email'] : '';
    $DrId = isset($DoctorData['id']) ? $DoctorData['id'] : '';

@endphp
<!DOCTYPE html>
<html lang="en">

<!-- doccure/doctor-dashboard.html  30 Nov 2019 04:12:03 GMT -->
<style>
    #loader-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
        display: flex;
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
        z-index: 9999; /* Ensure it's on top of everything */
        display: none; /* Initially hidden */
    }

    .loader {
        border: 8px solid #f3f3f3; /* Light grey */
        border-top: 8px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 60px;
        height: 60px;
        animation: spin 1s linear infinite; /* Spin animation */
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<head>
    <meta charset="utf-8">
    <title>@yield('doctor-page-title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/doctor/theme/css/bootstrap.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/doctor/theme/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/doctor/theme/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/doctor/theme/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Datatables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/plugins/datatables/datatables.min.css') }}">
</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div id="loader-container">
            <div class="loader"></div>
        </div>
        <!-- Header -->
        <header class="header">
            {{-- @include('doctor.layouts.navbar') --}}
        </header>
        <!-- /Header -->

        <!-- Breadcrumb -->
        <div class="breadcrumb-bar">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-12 col-12">
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index-2.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </nav>
                        <h2 class="breadcrumb-title">@yield('page')</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Breadcrumb -->

        <!-- Page Content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5 col-lg-4 col-xl-3 theiaStickySidebar">
                        <!-- Profile Sidebar -->
                        @include('doctor.layouts.sidebar')
                        <!-- /Profile Sidebar -->
                    </div>
                    <div class="col-md-7 col-lg-8 col-xl-9">
                        @yield('doctor-content')
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Main Wrapper -->
    <!-- Appointment Details Modal -->
    @yield('modal-content')
    <!-- End Appointment Details Modal -->
    <!-- jQuery -->
    <script type="text/javascript">
        var BASE_URL = "{{ url('/') }}";
    </script>
    <script src="{{ asset('assets/doctor/theme/js/jquery.min.js') }}"></script>
    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/doctor/theme/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/doctor/theme/js/bootstrap.min.js') }}"></script>

    <!-- Sticky Sidebar JS -->
    <script src="{{ asset('assets/doctor/theme/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
    <script src="{{ asset('assets/doctor/theme/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>
    {{-- toastr --}}
    <link rel="stylesheet" href="{{ asset('assets/admin/theme/cdnFiles/toastr.css') }}" />
    <script src="{{ asset('assets/admin/theme/cdnFiles/toastr.min.js') }}"></script>

    {{-- validate JS  --}}
    <script src="{{ asset('assets/admin/theme/cdnFiles/validate.min.js') }}"></script>
    <script src="{{ asset('assets/admin/theme/cdnFiles/additional-methods.min.js') }}"></script>

    <!-- Datatables JS -->
    <script src="{{ asset('assets/admin/theme/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/theme/plugins/datatables/datatables.min.js') }}"></script>

    <!-- Circle Progress JS -->
    <script src="{{ asset('assets/doctor/theme/js/circle-progress.min.js') }}"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/doctor/theme/js/script.js') }}"></script>

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    @yield('doctor-js')
</body>
<script>
    function showLoader() {
        document.getElementById('loader-container').style.display = 'flex';
    }

    function hideLoader() {
        document.getElementById('loader-container').style.display = 'none';
    }
</script>

<!-- doccure/doctor-dashboard.html  30 Nov 2019 04:12:09 GMT -->

</html>

@php
    $currentRouteName = \Route::currentRouteName();
    $DoctorData = session('doctors_data');
    $DoctorRole = isset($DoctorData['role']) ? $DoctorData['role'] : '';
    $DoctorName = isset($DoctorData['name']) ? $DoctorData['name'] : '';
    $DoctorEmail = isset($DoctorData['email']) ? $DoctorData['email'] : '';
@endphp
<!DOCTYPE html> 
<html lang="en">
	
<!-- doccure/doctor-dashboard.html  30 Nov 2019 04:12:03 GMT -->
<head>
		<meta charset="utf-8">
		<title>@yield('doctor-page-title')</title>
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
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
	
	</head>
	<body>

		<!-- Main Wrapper -->
		<div class="main-wrapper">
		
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
							<h2 class="breadcrumb-title">Dashboard</h2>
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
		<!-- jQuery -->
        <script type="text/javascript">
            var BASE_URL = "{{ url('/') }}";
        </script>
		<script src="{{ asset('assets/doctor/theme/js/jquery.min.js') }}"></script>
		
		<!-- Bootstrap Core JS -->
		<script src="{{ asset('assets/doctor/theme/js/popper.min.js')}}"></script>
		<script src="{{ asset('assets/doctor/theme/js/bootstrap.min.js')}}"></script>
		
		<!-- Sticky Sidebar JS -->
        <script src="{{ asset('assets/doctor/theme/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
        <script src="{{ asset('assets/doctor/theme/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js')}}"></script>
		
		<!-- Circle Progress JS -->
		<script src="{{ asset('assets/doctor/theme/js/circle-progress.min.js') }}"></script>
		
		<!-- Custom JS -->
		<script src="{{ asset('assets/doctor/theme/js/script.js')}}"></script>
		@yield('doctor-js')
	</body>

<!-- doccure/doctor-dashboard.html  30 Nov 2019 04:12:09 GMT -->
</html>
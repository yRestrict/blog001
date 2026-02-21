<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>
			@hasSection('pageTitle')
				@yield('pageTitle') - {{ $siteSetting->site_title ?? config('app.name') }}
			@else
				{{ $siteSetting->site_title ?? config('app.name') }}
			@endif
		</title>
		

		<!-- Site favicon -->
		@if($siteSetting?->favicon())
			<link rel="icon" type="image/png" href="{{ $siteSetting->favicon() }}">
		@endif
		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/bootstrap.min.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/style.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/icon-font.min.css') }}" />
		
        @stack('stylesheets')
	</head>
	<body class="login p-0 py-5">
		<x-header/>
		{{-- <div class="login-header box-shadow">
			<div class="container-fluid d-flex justify-content-between align-items-center">
				<div class="login-menu">
					<ul>
						<li><a href="register.html">Register</a></li>
					</ul>
				</div>
			</div>
		</div> --}}
		<div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="{{ asset('frontend/vendors/images/login-page-img.png') }}" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
                        @yield('content')
					</div>
				</div>
			</div>
		</div>
		
		<!-- js -->
		<script src="{{ asset('frontend/vendors/scripts/jquery-3.5.1.min.js') }}"></script>
		<script src="{{ asset('frontend/vendors/scripts/popper.min.js') }}"></script>
		<script src="{{ asset('frontend/vendors/scripts/bootstrap.min.js') }}"></script> 
		<script src="{{ asset('frontend/vendors/scripts/switch.js') }}"></script>
        @stack('scripts')
	
	</body>
</html>

<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>
			@hasSection('pageTitle')
				@yield('pageTitle') - {{ $settings->site_title ?? config('app.name') }}
			@else
				{{ $settings->site_title ?? config('app.name') }}
			@endif
		</title>
		

		<!-- Site favicon -->
		@if($settings?->favicon())
			<link rel="icon" type="image/png" href="{{ $settings->favicon() }}">
		@endif
		<!-- Mobile Specific Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>

		<!-- Google Font -->
		<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/bootstrap.min.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/style.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendors/styles/icon-font.min.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset("frontend/vendors/styles/owl.carousel.css") }}"/>
		
        @stack('stylesheets')
	</head>
	<body class="login p-0 py-5">
		<x-header/>

		@yield('content')
		{{-- <x-footer/> --}}
						
		<!-- js -->
		<script src="{{ asset('frontend/vendors/scripts/jquery-3.5.1.min.js') }}"></script>
		<script src="{{ asset('frontend/vendors/scripts/popper.min.js') }}"></script>
		<script src="{{ asset("frontend/vendors/scripts/owl.carousel.min.js") }}"></script>		
		<script src="{{ asset('frontend/vendors/scripts/bootstrap.min.js') }}"></script> 
		<script src="{{ asset('frontend/vendors/scripts/switch.js') }}"></script>
        @stack('scripts')
	
	</body>
</html>

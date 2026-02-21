@extends('auth.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : __('messages.login_title'))
@section('content')
<div class="login-box box-shadow border-radius-10">
   <div class="login-title">
      <h2 class="text-center mb-4">{{ __('messages.login_title') }}</h2>
   </div>
   <form action="{{ route('admin.login_handler') }}" method="POST">

      <x-form-alerts></x-form-alerts>
      @csrf

      <!-- Username/Email Field -->
      <div class="input-group custom mb-1">
         <div class="input-group-prepend">
            <span class="input-group-text border-end-0">
               <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#6c757d" viewBox="0 0 16 16">
                  <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 6 10c-4.29 0-5.516.68-6.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
               </svg>
            </span>
         </div>
         <input 
            type="text" 
            class="form-control form-control-lg border-start-0" 
            placeholder="{{ __('messages.username_email_placeholder') }}" 
            name="login_id" 
            value="{{ old('login_id') }}"
         >
      </div>
      @error('login_id')
         <div class="mb-2">
               <span class="text-danger">{{ $message }}</span>
         </div>
      @enderror

      <!-- Password Field -->
      <div class="input-group custom mb-2 mt-2">
         <div class="input-group-prepend">
            <span class="input-group-text border-end-0">
               <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#6c757d" viewBox="0 0 16 16">
                  <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
               </svg>
            </span>
         </div>
         <input type="password" class="form-control form-control-lg js-password" placeholder="{{ __('messages.password_placeholder') }}" name="password">
         <div class="input-group-append custom">
            <button type="button" class="input-group-text togglePassword">
               <i class="fa fa-eye"></i>
            </button>
         </div>
      </div>
      @error('password')
         <div class="mb-2">
               <span class="text-danger">{{ $message }}</span>
         </div>
      @enderror

      <!-- Remember & Forgot Password -->
      <div class="form-row align-items-center mb-4">
         <div class="col-6">
            <div class="custom-control custom-checkbox">
               <input type="checkbox" class="custom-control-input" id="remember" name="remember">
               <label class="custom-control-label" for="remember">
                  <small style="font-weight: 500;">{{ __('messages.remember_me') }}</small>
               </label>
            </div>
         </div>
         <div class="col-6 text-right">
            <a href="{{ route('admin.forgot') }}" class="text-secondary" style="text-decoration: none;">
               <small>{{ __('messages.forgot_password') }}</small>
            </a>
         </div>
      </div>

      <!-- Submit Button -->
      <div class="form-group">
         <button type="submit" class="btn btn-primary btn-block shadow-sm" id="submitBtn" style="font-weight: 700; border-radius: 8px; padding: 12px;">
            <span id="btnText">{{ __('messages.sign_in') }}</span>
            <div class="spinner-border spinner-border-sm ml-2 d-none" role="status" id="loadingSpinner">
               <span class="sr-only">{{ __('messages.loading') }}</span>
            </div>
         </button>
      </div>
   </form>
</div>


@endsection
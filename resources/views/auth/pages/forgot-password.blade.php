@extends('auth.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : __('messages.forgot_password_title'))
@section('content')

<div class="login-box box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">{{ __('messages.forgot_password_title') }}</h2>
    </div>
    <h6 class="mb-20">
        {{ __('messages.forgot_password_subtitle') }}
    </h6>
    <form action="{{ route('admin.send_password_reset_link') }}" method="POST">
        <x-form-alerts></x-form-alerts>
        @csrf

        <div class="input-group custom mb-2">
            <input type="text" class="form-control form-control-lg" placeholder="{{ __('messages.email_placeholder') }}" name="email" value="{{ old('email') }}">
            <div class="input-group-append custom">
                <span class="input-group-text"><i class="fa fa-envelope-o" aria-hidden="true"></i></span>
            </div>
        </div>

        @error('email')
        <div class="mb-2">
            <span class="text-danger d-block">{{ $message }}</span>
        </div>
        @enderror

        <div class="row align-items-center">
            <div class="col-12 mb-3">
                <div class="input-group">
                    <input class="btn btn-primary btn-block shadow-sm" id="submitBtn" type="submit" value="{{ __('messages.send_reset_link') }}">
                </div>
            </div>

            <div class="col-12">
                <div class="back-login-wrapper">
                    <a href="{{ route('admin.login') }}" class="btn-back-login">
                        {{ __('messages.back_to_login') }}
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
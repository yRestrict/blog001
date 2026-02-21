@extends('auth.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : __('messages.reset_password_title'))
@section('content')
<div class="login-box box-shadow border-radius-10">
    <div class="login-title">
        <h2 class="text-center text-primary">{{ __('messages.reset_password_title') }}</h2>
    </div>
    <h6 class="mb-20">{{ __('messages.reset_password_subtitle') }}</h6>
    <form action="{{ route('admin.reset_password_handler') }}" method="POST">

        <x-form-alerts></x-form-alerts>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group custom mb-3">
            <input type="password" class="form-control form-control-lg js-password" placeholder="{{ __('messages.new_password_placeholder') }}" name="new_password">
            <div class="input-group-append custom">
                <button type="button" class="input-group-text togglePassword">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        @error('new_password')
            <div class="mb-2">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="input-group custom mb-3">
            <input type="password" class="form-control form-control-lg js-password" placeholder="{{ __('messages.confirm_password_placeholder') }}" name="new_password_confirmation">
            <div class="input-group-append custom">
                <button type="button" class="input-group-text togglePassword">
                    <i class="fa fa-eye"></i>
                </button>
            </div>
        </div>

        @error('new_password_confirmation')
            <div class="mb-2">
                <span class="text-danger">{{ $message }}</span>
            </div>
        @enderror

        <div class="row align-items-center mt-2">
            <div class="col-12">
                <div class="input-group">
                    <input class="btn btn-primary btn-block" id="submitBtn" type="submit" value="{{ __('messages.submit') }}">
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\HomeController as DashboardHomeController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\MenuController;
use App\Http\Controllers\Dashboard\UserController;

Route::get('/', function () {
    return view('welcome');
});

/**
 * Testando Rotas 
 */

Route::view('/exemple-page', 'exemple-page');
Route::view('/exemple-auth', 'exemple-auth');
Route::view('/exemple-menu', 'exemple-menu');



Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::middleware(['guest', 'preventBackHistory'])->group(function () {
        // Login
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login_handler');

        // Esqueci a Senha
        Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot');
        Route::post('/send-password-reset-link', [ForgotPasswordController::class, 'sendResetLink'])->name('send_password_reset_link');

        // Resetar Senha
        Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password_reset_form');
        Route::post('/reset-password-handler', [ResetPasswordController::class, 'resetPassword'])->name('reset_password_handler');
    });

    Route::middleware(['auth', 'preventBackHistory'])->group(function () {
        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardHomeController::class, 'adminDashboard'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'profileView'])->name('profile');
        Route::post('/update-personal-picture', [ProfileController::class, 'UpdateProfilePicture'])->name('update_profile_picture');


        Route::get('/settings', [SettingController::class, 'generalSettings'])->name('settings');


        // Menus
        Route::get('/header', [MenuController::class, 'headerMenu'])->name('header');
        Route::get('/footer', [MenuController::class, 'footerMenu'])->name('footer');


        // Usuários — só owner acessa
        Route::middleware(['role:owner'])->prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/ban', [UserController::class, 'ban'])->name('ban');
            Route::patch('/{user}/promote', [UserController::class, 'promote'])->name('promote');
        });





    });
});

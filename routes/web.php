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
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\PostController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\CommentController;
use App\Http\Controllers\Frontend\HomeController as FrontendHomeController;
use App\Http\Controllers\Frontend\UserController as FrontendUserController; 
use App\Http\Controllers\Frontend\PostController as FrontendPostController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\TagController as FrontendTagController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
use App\Http\Controllers\Dashboard\SidebarController;

// Route::get('/', function () {
//     return view('welcome');
// });

/**
 * Páginas de exemplo/teste
 */
Route::view('/exemple-page', 'exemple-page');
Route::view('/exemple-auth', 'exemple-auth');
Route::view('/exemple-menu', 'exemple-menu');
Route::view('/exemple-master', 'exemple-master');


Route::name('frontend.')->group(function () {
    Route::get('/', [FrontendHomeController::class, 'index'])->name('home');
    Route::get("/user/{username}", [FrontendUserController::class, "index"])->name("user");
    Route::get("/search", [SearchController::class, "index"])->name("search");
    Route::get("/post/{slug}", [FrontendPostController::class, "index"])->name("post");
    Route::post("/comment/{id}", [FrontendCommentController::class, "index"])->name("comment");
    Route::post("/comment-reply", [FrontendCommentController::class, "reply"])->name("comment.reply");
    Route::get("/category/{slug}", [FrontendCategoryController::class, "index"])->name("category");
    Route::get("/tag/{id}", [FrontendTagController::class, "index"])->name("tag");
    Route::get("/search", [SearchController::class, "index"])->name("search");



});



Route::prefix('admin')->name('admin.')->group(function () {

    // ─── Rotas de convidado ───────────────────────────────────────────────────
    Route::middleware(['guest', 'preventBackHistory'])->group(function () {

        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login_handler');

        Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('forgot');
        Route::post('/send-password-reset-link', [ForgotPasswordController::class, 'sendResetLink'])->name('send_password_reset_link');

        Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password_reset_form');
        Route::post('/reset-password-handler', [ResetPasswordController::class, 'resetPassword'])->name('reset_password_handler');
    });

    // ─── Rotas autenticadas ───────────────────────────────────────────────────
    Route::middleware(['auth', 'preventBackHistory', 'checkBanned'])->group(function () {

        Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardHomeController::class, 'adminDashboard'])->name('dashboard');

        // Perfil
        Route::get('/profile', [ProfileController::class, 'profileView'])->name('profile');
        Route::post('/update-personal-picture', [ProfileController::class, 'UpdateProfilePicture'])->name('update_profile_picture');

        // ─── Rotas de owner ───────────────────────────────────────────────────
        Route::middleware(['role:owner'])->group(function () {

        Route::get('/sidebars', [SidebarController::class, 'index'])
            ->name('sidebars');


            // Categorias
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [CategoryController::class, 'categoriesPage'])->name('index');
                Route::get('/trash', [CategoryController::class, 'categoriesTrash'])->name('trash');
            });

            Route::prefix('sidebar')->name('sidebar.')->group(function () {
                Route::get('/', [CategoryController::class, 'categoriesPage'])->name('index');
            });

            // Posts
            Route::prefix('posts')->name('posts.')->group(function () {
                Route::get('/', [PostController::class, 'PostPage'])->name('index');
                Route::get('/trash', [PostController::class, 'postTrash'])->name('trash');
                Route::get('/create', [PostController::class, 'postCreate'])->name('create');
                Route::post('/', [PostController::class, 'postStore'])->name('store');
                Route::get('/{post}/edit', [PostController::class, 'postEdit'])->name('edit');
                Route::put('/{post}', [PostController::class, 'postUpdate'])->name('update');
                Route::delete('/{post}', [PostController::class, 'postDestroy'])->name('destroy');
            });

            // Tags
            Route::prefix('tags')->name('tags.')->group(function () {
                Route::get('/tags/search', [PostController::class, 'searchTags'])->name('tags.search');
                Route::get('/', [TagController::class, 'index'])->name('index');
            });

            // Comentários (Moderação)
            Route::prefix('comments')->name('comments.')->group(function () {
                Route::get('/', [CommentController::class, 'index'])->name('index');
            });

            // Configurações
            Route::get('/settings', [SettingController::class, 'generalSettings'])->name('settings');
            Route::get('/header', [MenuController::class, 'headerMenu'])->name('header');
            Route::get('/footer', [MenuController::class, 'footerMenu'])->name('footer');

            // Usuários
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::get('/create', [UserController::class, 'create'])->name('create');
                Route::post('/', [UserController::class, 'store'])->name('store');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
            });
        });
    });
});
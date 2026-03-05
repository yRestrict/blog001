<?php

namespace App\Providers;

use App\Models\Sidebar;
use App\Models\User;
use App\Observers\SidebarObserver;
use App\Policies\UserPolicy;
use App\Services\SidebarService;
use App\View\Composers\SidebarViewComposer;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SidebarService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sidebar::observe(SidebarObserver::class);

        View::composer('frontend.*', SidebarViewComposer::class);





        //Redirect an authenticated user dashboard if tries to access login page
        RedirectIfAuthenticated::redirectUsing(function(){
            return route('admin.dashboard');
        });

        Authenticate::redirectUsing(function(){
            Session::flash('fail', __('auth.login_required'));
            return route('admin.login');
        });

        Gate::policy(User::class, UserPolicy::class);
    }
}

<?php

namespace App\Providers;

use App\Notification;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
// use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        App::setLocale('sp');
        //
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
        view()->composer('*', function ($view) {
            $Cnotification_count = collect();
            $user = null;
            $role = null;
            try {
                $sentinelUser = Sentinel::getUser();
                if ($sentinelUser) {
                    $user = $sentinelUser;
                    $role = $sentinelUser->roles[0]->slug ?? null;
                    $Cnotification_count = Notification::with(['user'])
                        ->where('to_user', $sentinelUser->id)
                        ->where('read_at', null)
                        ->take(10)
                        ->orderBy('id', 'desc')
                        ->get();
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('AppServiceProvider ViewComposer error: ' . $e->getMessage());
            }
            $view->with([
                'Cnotification_count' => $Cnotification_count,
                'user'                => $view->getData()['user'] ?? $user,
                'role'                => $view->getData()['role'] ?? $role,
            ]);
        });
    }
}
    
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
            $Cnotification_count = [];
            if (Sentinel::getUser()) {
                $user_id =Sentinel::getUser()->id;
                $Cnotification_count = Notification::with(['user'])->where(['to_user'=>$user_id])->where('read_at','=',null)->take(10)->orderBy('id','desc')->get();
            }
            $data =
            [
                'Cnotification_count' => $Cnotification_count,
            ];
        $view->with($data);
        });
    }
}
    
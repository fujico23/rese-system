<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;

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
        //どのページでもログインしているユーザーのrole_idを参照する
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('role_id', Auth::user()->role_id);
            }
        });

        //リメンバーミー機能
        Fortify::authenticateUsing(function ($request) {
            $validated = Auth::validate($credentials = [
                'email' => $request->email,
                'password' => $request->password
            ]);

            return $validated ? Auth::getProvider()->retrieveByCredentials($credentials) : null;
        });
    }
}

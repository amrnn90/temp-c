<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Admin\Http\Middleware\Authenticate;
use App\Admin\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    protected function registerRoutes()
    {

        Route::namespace('App\Admin\Http\Controllers')
            ->prefix('admin')
            ->name('admin.')
            ->middleware('web')
            ->group(function () {
                Route::middleware(RedirectIfAuthenticated::class)
                    ->group(function () {
                        Route::get('login', 'LoginController@showLoginForm')->name('login');
                        Route::post('login', 'LoginController@login');
                    });

                Route::middleware(Authenticate::class)
                    ->group(function () {

                        Route::prefix('api')
                            ->name('api.')
                            ->group(function () {
                                // Route::get('/', function(){return null;})->name('base');
                                Route::get('{resource}', 'AdminController@index')->name('index');
                                Route::delete('{resource}/{id}', 'AdminController@destroy')->name('destroy');
                            });

                        Route::post('logout', 'LoginController@logout')->name('logout');
                        Route::get('{any?}', 'AdminController@spa')->where('any', '.*')->name('spa');
                    });
            });
    }
}

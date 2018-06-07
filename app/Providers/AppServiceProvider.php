<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use App\Console\Commands\JWTGenerateCommand;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        if (env('HTTPS')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerJWTCommand();
    }

    protected function registerJWTCommand()
    {
        $this->app->singleton('tymon.jwt.generate', function () {
            return new JWTGenerateCommand();
        });
    }
}

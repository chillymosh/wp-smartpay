<?php

namespace SmartPay\Providers;

use SmartPay\Framework\Support\ServiceProvider;
use SmartPay\Modules\Frontend\Common;
use SmartPay\Modules\Shortcode\Shortcode;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Common::class, function ($app) {
            return new Common($app);
        });

        $this->app->singleton(Shortcode::class, function ($app) {
            return new Shortcode($app);
        });
    }

    public function boot()
    {
        $this->app->make(Common::class);
        $this->app->make(Shortcode::class);
    }
}
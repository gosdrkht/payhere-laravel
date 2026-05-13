<?php

namespace LeadingEdge\PayHere;

use Illuminate\Support\ServiceProvider;

class PayHereServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/payhere.php',
            'payhere'
        );

        $this->app->singleton('payhere', function ($app) {
            return new PayHere(config('payhere'));
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/payhere.php' => config_path('payhere.php'),
            ], 'payhere-config');
        }
    }
}
<?php

namespace Pragmatiqu\Auth;

use Illuminate\Support\ServiceProvider;
use Pragmatiqu\Auth\Contracts\LoginSuccessProviderInterface;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void {
        $this->mergeConfigFrom(__DIR__.'/../config.php', 'ui5-auth');

        $this->app->singleton(
            LoginSuccessProviderInterface::class,
            config('ui5-auth.success_url_provider', LoginSuccessProvider::class)
        );
    }

    public function boot(): void
    {

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config.php' => config_path('ui5-auth.php')], 'ui5-config');
        }

    }
}

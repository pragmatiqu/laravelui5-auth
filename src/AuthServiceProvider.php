<?php

namespace Pragmatiqu\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use LaravelUi5\Core\Ui5\Ui5InfrastructureCollector;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');

        $this->app->make(Ui5InfrastructureCollector::class)
            ->add(AuthModule::class);
    }
}

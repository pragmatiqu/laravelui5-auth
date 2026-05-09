<?php

namespace LaravelUi5\Auth;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use LaravelUi5\Core\Ui5\Ui5InfrastructureCollector;
use LaravelUi5\Auth\Contracts\IntentDispenserInterface;
use LaravelUi5\Auth\Intents\Default\DefaultIntentDispenser;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singletonIf(IntentDispenserInterface::class, DefaultIntentDispenser::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/auth.php');

        ResetPassword::createUrlUsing(fn (CanResetPassword $notifiable, string $token) => route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]));

        $this->app->make(Ui5InfrastructureCollector::class)
            ->add(AuthModule::class);
    }
}

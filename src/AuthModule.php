<?php

namespace LaravelUi5\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5Module;
use LaravelUi5\Core\Ui5\Capabilities\Ui5InfrastructureContributorInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5Infrastructure;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class AuthModule extends AbstractUi5Module implements Ui5Infrastructure, Ui5InfrastructureContributorInterface
{
    /**
     * The module's root (`core/module-artifacts-v1.0` D16); Auth declares no other artifact.
     */
    public const string ARTIFACT_ROOT = AuthApp::class;

    public function requiresAuth(): bool
    {
        return false;
    }

    public function getInfrastructureKey(): string
    {
        return 'auth';
    }

    public function contribute(Ui5RegistryInterface $registry): array
    {
        return [
            'logout' => route('logout'),
        ];
    }
}

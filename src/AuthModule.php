<?php

namespace LaravelUi5\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5Module;
use LaravelUi5\Core\Ui5\Capabilities\Ui5InfrastructureContributorInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5Infrastructure;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class AuthModule extends AbstractUi5Module implements Ui5Infrastructure, Ui5InfrastructureContributorInterface
{
    /**
     * The module's artifacts, root included (`core/module-artifacts-v1.0` D10).
     */
    public const array ARTIFACTS = [
        AuthApp::class,
    ];

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

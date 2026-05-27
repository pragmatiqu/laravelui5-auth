<?php

namespace LaravelUi5\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5Module;
use LaravelUi5\Core\Ui5\Capabilities\Ui5InfrastructureContributorInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5AppInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5Infrastructure;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class AuthModule extends AbstractUi5Module implements Ui5Infrastructure, Ui5InfrastructureContributorInterface
{
    public function requiresAuth(): bool
    {
        return false;
    }

    public function getApp(): ?Ui5AppInterface
    {
        return new AuthApp($this);
    }

    public function getCards(): array
    {
        return [];
    }

    public function getKpis(): array
    {
        return [];
    }

    public function getTiles(): array
    {
        return [];
    }

    public function getActions(): array
    {
        return [];
    }

    public function getResources(): array
    {
        return [];
    }

    public function getDashboards(): array
    {
        return [];
    }

    public function getReports(): array
    {
        return [];
    }

    public function getDialogs(): array
    {
        return [];
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

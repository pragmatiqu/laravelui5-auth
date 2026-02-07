<?php

namespace Pragmatiqu\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5Module;
use LaravelUi5\Core\Ui5\Contracts\Ui5AppInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5Infrastructure;
use Pragmatiqu\Auth\Actions\LoginAction;

class AuthModule extends AbstractUi5Module implements Ui5Infrastructure
{
    public function getName(): string
    {
        return 'io.pragmatiqu.auth';
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
        return [
            new LoginAction($this)
        ];
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
}

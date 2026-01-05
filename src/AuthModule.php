<?php

namespace Pragmatiqu\Auth;

use LaravelUi5\Core\Ui5\Contracts\Ui5AppInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5ArtifactInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5LibraryInterface;
use LaravelUi5\Core\Ui5\Ui5Module;
use Pragmatiqu\Auth\Actions\Login\Action;
use Pragmatiqu\Auth\Resources\Login\Resource;

class AuthModule extends Ui5Module
{
    public function hasApp(): bool
    {
        return true;
    }

    public function getApp(): ?Ui5AppInterface
    {
        return new AuthApp($this);
    }

    public function hasLibrary(): bool
    {
        return false;
    }

    public function getLibrary(): ?Ui5LibraryInterface
    {
        return null;
    }

    public function getArtifactRoot(): Ui5ArtifactInterface
    {
        return $this->getApp();
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
            new Actions\Login\Action($this),
            new Actions\ForgotPassword\Action($this),
        ];
    }

    public function getResources(): array
    {
        return [];
    }
}

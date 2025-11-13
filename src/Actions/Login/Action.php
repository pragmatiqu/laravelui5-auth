<?php

namespace Pragmatiqu\Auth\Actions\Login;

use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Enums\HttpMethod;
use LaravelUi5\Core\Ui5\Contracts\ActionHandlerInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5ModuleInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5ActionInterface;

class Action implements Ui5ActionInterface
{

    public function __construct(protected Ui5ModuleInterface $module)
    {
    }

    public function getModule(): ?Ui5ModuleInterface
    {
        return $this->module;
    }

    public function getNamespace(): string
    {
        return 'io.pragmatiqu.auth.actions.login';
    }

    public function getType(): ArtifactType
    {
        return ArtifactType::Action;
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getTitle(): string
    {
        return 'Login';
    }

    public function getDescription(): string
    {
        return 'Action for Login';
    }

    public function getSlug(): string
    {
        return 'login';
    }

    public function getMethod(): HttpMethod
    {
        return HttpMethod::POST;
    }

    public function getHandler(): ActionHandlerInterface
    {
        return new Handler();
    }
}

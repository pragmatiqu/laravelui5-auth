<?php

namespace LaravelUi5\Auth\Actions;

use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Enums\HttpMethod;
use LaravelUi5\Core\Ui5\AbstractUi5Action;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;
use LaravelUi5\Auth\Actions\Handler\LoginHandler;

class LoginAction extends AbstractUi5Action
{

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

    public function getMethod(): HttpMethod
    {
        return HttpMethod::POST;
    }

    public function getHandler(): ActionHandlerInterface
    {
        return new LoginHandler();
    }
}

<?php

namespace Pragmatiqu\Auth\Actions\Login;

use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Enums\HttpMethod;
use LaravelUi5\Core\Ui5\AbstractUi5Action;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;

class Action extends AbstractUi5Action
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
        return new LoginHandler();
    }
}

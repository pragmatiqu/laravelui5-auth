<?php

namespace Pragmatiqu\Auth\Actions;

use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Enums\HttpMethod;
use LaravelUi5\Core\Ui5\AbstractUi5Action;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;
use Pragmatiqu\Auth\Actions\Handler\LogoutHandler;

class LogoutAction extends AbstractUi5Action
{

    public function getNamespace(): string
    {
        return 'io.pragmatiqu.auth.actions.logout';
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
        return 'Logout';
    }

    public function getDescription(): string
    {
        return 'Action for Logout';
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
        return new LogoutHandler();
    }
}

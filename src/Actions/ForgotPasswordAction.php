<?php

namespace Pragmatiqu\Auth\Actions;

use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Enums\HttpMethod;
use LaravelUi5\Core\Ui5\AbstractUi5Action;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;
use Pragmatiqu\Auth\Actions\Handler\ForgotPasswordHandler;

class ForgotPasswordAction extends AbstractUi5Action
{

    public function getNamespace(): string
    {
        return 'io.pragmatiqu.auth.actions.forgot-password';
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
        return 'ForgotPassword';
    }

    public function getDescription(): string
    {
        return 'Action for ForgotPassword';
    }

    public function getMethod(): HttpMethod
    {
        return HttpMethod::POST;
    }

    public function getHandler(): ActionHandlerInterface
    {
        return new ForgotPasswordHandler();
    }
}

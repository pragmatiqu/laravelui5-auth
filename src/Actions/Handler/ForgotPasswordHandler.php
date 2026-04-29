<?php

namespace LaravelUi5\Auth\Actions\Handler;

use LaravelUi5\Core\Ui5\AbstractConfigurable;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;
use LaravelUi5\Auth\Requests\ForgotPasswordRequest;

class ForgotPasswordHandler extends AbstractConfigurable implements ActionHandlerInterface
{
    public function handle(ForgotPasswordRequest $request): array
    {
        $request->sendResetLink();

        return [
            'message' => 'reset_link_sent',
        ];
    }
}

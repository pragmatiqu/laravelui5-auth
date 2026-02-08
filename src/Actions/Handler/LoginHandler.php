<?php

namespace Pragmatiqu\Auth\Actions\Handler;

use LaravelUi5\Core\Ui5\AbstractConfigurable;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;
use Pragmatiqu\Auth\Contracts\LoginSuccessProviderInterface;
use Pragmatiqu\Auth\Requests\LoginRequest;

class LoginHandler extends AbstractConfigurable implements ActionHandlerInterface
{
    public function handle(LoginRequest $request, LoginSuccessProviderInterface $provider): array
    {
        $request->authenticate();

        $request->session()->regenerate();

        $path = $provider->redirectUrl($request);

        return [
            'message' => 'login_success',
            'redirect' => $path,
        ];
    }
}

<?php

namespace Pragmatiqu\Auth;

use Pragmatiqu\Auth\Contracts\LoginSuccessProviderInterface;

class LoginSuccessProvider implements Contracts\LoginSuccessProviderInterface
{
    public function redirectUrl(): string
    {
        return '/dashboard';
    }
}

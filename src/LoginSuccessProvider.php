<?php

namespace Pragmatiqu\Auth;

use Illuminate\Http\Request;
use Pragmatiqu\Auth\Contracts\LoginSuccessProviderInterface;

class LoginSuccessProvider implements LoginSuccessProviderInterface
{
    /**
     * Standard redirect url provider based on Laravel auth.
     *
     * @param Request $request
     * @return string
     */
    public function redirectUrl(Request $request): string
    {
        return $request->session()->pull('url.intended', route('dashboard'));
    }
}

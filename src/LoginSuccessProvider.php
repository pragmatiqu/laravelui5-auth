<?php

namespace LaravelUi5\Auth;

use Illuminate\Http\Request;
use LaravelUi5\Auth\Contracts\LoginSuccessProviderInterface;

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

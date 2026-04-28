<?php

namespace LaravelUi5\Auth\Contracts;

use Illuminate\Http\Request;

/**
 * Provides disambiguation for Login Success redirect URLs.
 */
interface LoginSuccessProviderInterface
{
    /**
     * @return string URL after successful login
     */
    public function redirectUrl(Request $request): string;
}

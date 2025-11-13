<?php

namespace Pragmatiqu\Auth\Contracts;

/**
 * Provides disambiguation for Login Success redirect URLs.
 */
interface LoginSuccessProviderInterface
{
    /**
     * @return string URL after successful login
     */
    public function redirectUrl(): string;
}

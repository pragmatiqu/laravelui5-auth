<?php

namespace LaravelUi5\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use LaravelUi5\Auth\Intents\Intent;

interface IntentDispenserInterface
{
    /**
     * Returns the next intent to satisfy. Always returns an Intent — never null.
     * When nothing else is pending, returns a RedirectIntent pointing at the
     * user's final destination.
     *
     * Called once after credentials are validated, then again after every
     * intent satisfaction until a RedirectIntent is returned.
     */
    public function dispense(Authenticatable $user, Request $request): Intent;
}

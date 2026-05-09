<?php

namespace LaravelUi5\Auth\Intents\Default;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use LaravelUi5\Auth\Contracts\IntentDispenserInterface;
use LaravelUi5\Auth\Intents\Intent;
use LaravelUi5\Auth\Intents\RedirectIntent;

final class DefaultIntentDispenser implements IntentDispenserInterface
{
    public function dispense(Authenticatable $user, Request $request): Intent
    {
        $target = $request->session()->pull('url.intended', route('dashboard'));

        return new RedirectIntent($target);
    }
}

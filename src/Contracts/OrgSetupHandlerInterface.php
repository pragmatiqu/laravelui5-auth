<?php

namespace LaravelUi5\Auth\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use LaravelUi5\Auth\Intents\Results\IntentResult;
use LaravelUi5\Auth\Requests\OrgSetupRequest;

/**
 * Implemented by host applications that opt into the OrgSetup intent.
 *
 * Auth ships no default — a host that dispenses OrgSetupIntent without
 * binding a handler will fail loud at request time. That's the desired
 * behavior; the closed catalog (see specs/ui5-auth-intents.md § 4) puts
 * the host on the hook for real-state coordination.
 */
interface OrgSetupHandlerInterface
{
    public function handle(Authenticatable $user, OrgSetupRequest $request): IntentResult;
}

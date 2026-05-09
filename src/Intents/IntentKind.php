<?php

namespace LaravelUi5\Auth\Intents;

enum IntentKind: string
{
    case OrgSetup = 'org_setup';
    case Redirect = 'redirect';
}

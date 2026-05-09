<?php

namespace LaravelUi5\Auth\Intents;

final readonly class OrgSetupIntent extends Intent
{
    public function __construct(public string $personName)
    {
        parent::__construct(IntentKind::OrgSetup);
    }

    public function payload(): array
    {
        return ['personName' => $this->personName];
    }
}

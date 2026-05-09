<?php

namespace LaravelUi5\Auth\Intents;

final readonly class RedirectIntent extends Intent
{
    public function __construct(public string $target)
    {
        parent::__construct(IntentKind::Redirect);
    }

    public function payload(): array
    {
        return ['target' => $this->target];
    }
}

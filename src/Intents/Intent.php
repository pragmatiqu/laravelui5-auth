<?php

namespace LaravelUi5\Auth\Intents;

abstract readonly class Intent
{
    public function __construct(
        public IntentKind $kind,
        public string $version = '1',
    ) {}

    /**
     * Returns the data the frontend needs to render this intent.
     * Each kind defines its own payload shape.
     */
    abstract public function payload(): array;

    public function serialize(): array
    {
        return [
            'kind'    => $this->kind->value,
            'version' => $this->version,
            'payload' => $this->payload(),
        ];
    }
}

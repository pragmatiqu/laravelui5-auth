<?php

namespace LaravelUi5\Auth\Intents\Results;

final readonly class IntentResult
{
    public const STATUS_SATISFIED        = 'satisfied';
    public const STATUS_NEEDS_MORE_INPUT = 'needs_more_input';
    public const STATUS_ERROR            = 'error';

    public function __construct(
        public string $status,
        public ?array $nextStep = null,
        public ?array $errors = null,
    ) {}

    public static function satisfied(): self
    {
        return new self(status: self::STATUS_SATISFIED);
    }

    public static function needsMoreInput(array $payload): self
    {
        return new self(status: self::STATUS_NEEDS_MORE_INPUT, nextStep: $payload);
    }

    public static function error(array $errors): self
    {
        return new self(status: self::STATUS_ERROR, errors: $errors);
    }
}

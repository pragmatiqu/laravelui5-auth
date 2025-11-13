<?php

namespace Pragmatiqu\Auth;

use LaravelUi5\Core\Ui5\AbstractLaravelUi5Manifest;

class AuthManifest extends AbstractLaravelUi5Manifest
{
    protected function enhanceFragment(string $module): array
    {
        return [
            'meta' => [
                'terms' => config('ui5-auth.terms'),
                'privacy' => config('ui5-auth.privacy'),
                'cookies' => config('ui5-auth.cookies'),
            ]
        ];
    }
}

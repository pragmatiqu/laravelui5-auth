<?php

namespace Pragmatiqu\Auth;


use LaravelUi5\Core\Ui5\AbstractManifest;

class AuthManifest extends AbstractManifest
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

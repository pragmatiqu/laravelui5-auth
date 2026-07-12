<?php

namespace LaravelUi5\Auth;

class AuthApp extends AuthAppBase
{
    public const string NAMESPACE   = 'com.laravelui5.auth';
    public const string VERSION     = '1.0.0';

    public function getResourceNamespaces(): array
    {
        return [
            'com.laravelui5.core',
        ];
    }

    public function getLaravelUiManifest(): string
    {
        return AuthManifest::class;
    }

    public function getVendor(): string
    {
        return 'Pragmatiqu IT GmbH';
    }
}

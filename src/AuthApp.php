<?php

namespace LaravelUi5\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5App;
use LaravelUi5\Core\Ui5\Capabilities\LaravelUi5ManifestInterface;

class AuthApp extends AbstractUi5App
{
    public const string NAMESPACE   = 'io.pragmatiqu.auth';
    public const string VERSION     = '1.0.0';
    public const string TITLE       = 'Login';
    public const string DESCRIPTION = 'UI5 Application io.pragmatiqu.auth';

    public function getUi5BootstrapAttributes(): array
    {
        return [
            'on-init'        => 'module:sap/ui/core/ComponentSupport',
            'compat-version' => 'edge',
            'frame-options'  => 'trusted',
            'async'          => 'true',
        ];
    }

    public function getResourceNamespaces(): array
    {
        return [
            'com.laravelui5.core',
        ];
    }

    public function getAdditionalHeadScript(): ?string
    {
        return <<<JS

JS;
    }

    public function getAdditionalInlineCss(): ?string
    {
        return <<<CSS

CSS;
    }

    public function getLaravelUiManifest(): LaravelUi5ManifestInterface
    {
        return app(AuthManifest::class);
    }

    public function getVendor(): string
    {
        return 'Pragmatiqu IT GmbH';
    }
}

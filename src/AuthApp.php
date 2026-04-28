<?php

namespace LaravelUi5\Auth;

use LaravelUi5\Core\Ui5\AbstractUi5App;
use LaravelUi5\Core\Ui5\Capabilities\LaravelUi5ManifestInterface;
use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Traits\HasAssetsTrait;

class AuthApp extends AbstractUi5App
{
    use HasAssetsTrait;

    public function getType(): ArtifactType
    {
        return ArtifactType::Application;
    }

    public function getNamespace(): string
    {
        return 'io.pragmatiqu.auth';
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function getTitle(): string
    {
        return 'io.pragmatiqu.auth';
    }

    public function getDescription(): string
    {
        return 'UI5 Application io.pragmatiqu.auth';
    }

    public function getUi5BootstrapAttributes(): array
    {
        return [
          'on-init' => 'module:sap/ui/core/ComponentSupport',
          'compat-version' => 'edge',
          'frame-options' => 'trusted',
          'async' => 'true',
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

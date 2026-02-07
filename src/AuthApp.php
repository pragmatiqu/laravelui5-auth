<?php

namespace Pragmatiqu\Auth;

use LaravelUi5\Core\Introspection\App\Ui5AppSource;
use LaravelUi5\Core\Enums\ArtifactType;
use LaravelUi5\Core\Traits\HasAssetsTrait;
use LaravelUi5\Core\Ui5\Capabilities\LaravelUi5ManifestInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5AppInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5ModuleInterface;

class AuthApp implements Ui5AppInterface
{
    use HasAssetsTrait;

    public function __construct(protected Ui5ModuleInterface $module)
    {
    }

    public function getModule(): Ui5ModuleInterface
    {
        return $this->module;
    }

    public function getSource(): Ui5AppSource
    {
        return $this->module->getSourceStrategy()->createAppSource($this->getVendor());
    }

    public function getManifestPath(): string
    {
        return $this->module->getSourceStrategy()->getSourcePath() . '/manifest.json';
    }

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
        return 'Pragmatiqu/Auth';
    }

    public function getDescription(): string
    {
        return 'Offers authentication capabilities for Laravel/UI5 applications.';
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
            'com.laravelui5.core'
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

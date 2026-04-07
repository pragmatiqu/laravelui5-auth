<?php

namespace Pragmatiqu\Auth;

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
        return '${version}';
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
        return array (
  'resource-roots' => '{
				"io.pragmatiqu.auth": "./"
			}',
  'on-init' => 'module:sap/ui/core/ComponentSupport',
  'compat-version' => 'edge',
  'frame-options' => 'trusted',
  'async' => 'true',
);
    }

    public function getResourceNamespaces(): array
    {
        return array (
);
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

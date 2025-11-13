<?php

namespace Pragmatiqu\Auth;

use Illuminate\Support\Facades\File;
use LaravelUi5\Core\Ui5\Contracts\LaravelUi5ManifestInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5AppInterface;
use LaravelUi5\Core\Ui5\Contracts\Ui5ModuleInterface;
use LaravelUi5\Core\Enums\ArtifactType;

class AuthApp implements Ui5AppInterface
{

    public function __construct(protected Ui5ModuleInterface $module)
    {
    }

    public function getModule(): ?Ui5ModuleInterface
    {
        return $this->module;
    }

    public function getSlug(): string
    {
        return $this->module->getSlug();
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
        return 'Auth';
    }

    public function getDescription(): string
    {
        return 'Ui5App generated via ui5:sca';
    }

    public function getUi5BootstrapAttributes(): array
    {
        return [
          'theme' => 'sap_horizon',
          'oninit' => 'module:io/pragmatiqu/auth/Component',
          'async' => 'true',
          'compatversion' => 'edge',
          'frameoptions' => 'trusted',
          'xx-waitfortheme' => 'true',
          'xx-supportedlanguages' => 'en',
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
sap.ui.getCore().attachInit(function () {
    sap.ui.core.Component.create({
      name: "io.pragmatiqu.auth",
      manifest: true,
      async: true
    }).then(function (oComponent) {
      new sap.ui.core.ComponentContainer({
        component: oComponent,
        height: "100%"
      }).placeAt("content");
    }).catch(function (err) {
      console.error("Component load failed:", err);
    });
});
JS;
    }

    public function getAdditionalInlineCss(): ?string
    {
        return <<<CSS
.bg-white {
    background-color: white
}
CSS;
    }

    public function getManifestPath(): string
    {
        return __DIR__ . '/../resources/app/manifest.json';
    }

    public function getLaravelUiManifest(): LaravelUi5ManifestInterface
    {
        return app(AuthManifest::class);
    }

    public function getAssetPath(string $filename): ?string
    {
        $path = __DIR__ . '/../resources/app/' . ltrim($filename, '/');
        return File::exists($path) ? $path : null;
    }

    public function getVendor(): string
    {
        return 'Vendor not supplied';
    }
}

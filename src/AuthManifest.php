<?php

namespace LaravelUi5\Auth;


use Illuminate\Support\Facades\Route;
use LaravelUi5\Core\Ui5\AbstractManifest;
use LaravelUi5\Core\Ui5\Capabilities\LaravelUi5ManifestKeys;

class AuthManifest extends AbstractManifest
{
    protected function contributeFragment(string $module): array
    {
        $path = public_path('assets/ci/logo-full.svg');

        if (file_exists($path)) {
            $asset = asset('assets/ci/logo-full.svg');
        } else {
            $asset = asset('vendor/laravelui5/auth/logo-full.svg');
        }

        return [
            LaravelUi5ManifestKeys::ROUTES => [
                'logo' => $asset,
                'logout' => route('logout'),
                'terms' => Route::has('terms') ? route('terms') : null,
                'privacy' => Route::has('privacy') ? route('privacy') : null,
                'cookies' => Route::has('cookies') ? route('cookies') : null,
            ]
        ];
    }
}

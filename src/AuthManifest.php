<?php

namespace LaravelUi5\Auth;

use Illuminate\Support\Facades\Route;
use LaravelUi5\Core\Ui5\AbstractManifest;
use LaravelUi5\Core\Ui5\Capabilities\LaravelUi5ManifestKeys;

class AuthManifest extends AbstractManifest
{
    protected function contributeFragment(string $module): array
    {
        // The consuming application's logo, rendered above the card on every auth
        // screen. There is deliberately no fallback: a package-shipped logo would
        // put a foreign brand on a customer's sign-in screen, which is worse than
        // no logo at all. `null` makes the frontend hide the image.
        $logo = file_exists(public_path('assets/ci/logo-full.svg'))
            ? asset('assets/ci/logo-full.svg')
            : null;

        return [
            LaravelUi5ManifestKeys::ROUTES => [
                'logo' => $logo,
                'terms' => Route::has('terms') ? route('terms') : null,
                'privacy' => Route::has('privacy') ? route('privacy') : null,
                'cookies' => Route::has('cookies') ? route('cookies') : null,
            ],
        ];
    }
}

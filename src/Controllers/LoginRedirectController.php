<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class LoginRedirectController
{
    /**
     * Redirects to Auth app.
     *
     * @param Ui5RegistryInterface $registry
     * @return RedirectResponse
     */
    public function __invoke(Ui5RegistryInterface $registry): RedirectResponse
    {
        $url = $registry->resolveIndexUrl('io.pragmatiqu.auth', '/');

        return redirect($url);
    }
}

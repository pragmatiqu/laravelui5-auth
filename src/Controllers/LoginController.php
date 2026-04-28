<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class LoginController
{
    /**
     * Redirects to Auth app.
     *
     * @param Ui5RegistryInterface $registry
     * @return RedirectResponse
     */
    public function __invoke(Ui5RegistryInterface $registry): RedirectResponse
    {
        $url = $registry->resolve('io.pragmatiqu.auth') . '/index.html';

        return redirect($url);
    }
}

<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class ResetPasswordController
{
    public function __invoke(Request $request, Ui5RegistryInterface $registry, string $token): RedirectResponse
    {
        $email = (string) $request->query('email', '');

        $base = $registry->resolve('io.pragmatiqu.auth');

        $url = $base . '/index.html#/set-password/' . urlencode($token) . '/' . urlencode($email);

        return redirect($url);
    }
}

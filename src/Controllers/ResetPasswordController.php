<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class ResetPasswordController
{
    public function __invoke(Request $request, Ui5RegistryInterface $registry, string $token): RedirectResponse
    {
        $email = (string) $request->query('email', '');

        // Pre-validate the token before sending the user into the UI5 form.
        // The POST-side handler validates again (source of truth), but catching
        // it here avoids landing the user on a form that will fail on submit.
        $broker = Password::broker();
        $user = $broker->getUser(['email' => $email]);

        if (!$user || !$broker->tokenExists($user, $token)) {
            return redirect()->route('login');
        }

        $base = $registry->resolve('io.pragmatiqu.auth');

        $url = $base . '/index.html#/set-password/' . urlencode($token) . '/' . urlencode($email);

        return redirect($url);
    }
}

<?php

namespace Pragmatiqu\Auth\Actions\Login;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use LaravelUi5\Core\Ui5\AbstractActionHandler;
use Pragmatiqu\Auth\Contracts\LoginSuccessProviderInterface;

class Handler extends AbstractActionHandler
{
    public function execute(): array
    {
        try {
            $credentials = request()->validate([
                'email' => ['required', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (!Auth::attempt($credentials, true)) {
                throw ValidationException::withMessages([
                    'email' => ['Invalid credentials.'],
                ]);
            }

            request()->session()->regenerate();

            $provider = app(LoginSuccessProviderInterface::class);

            return [
                'status' => 'Success',
                'message' => 'Login successful.',
                'redirect' => $provider->redirectUrl(),
            ];
        }
        catch (ValidationException $e) {
            return [
                'status' => 'Error',
                'message' => 'Login credentials incorrect.',
                'errors' => [
                    'message' => $e->getMessage(),
                ]
            ];
        }
    }
}

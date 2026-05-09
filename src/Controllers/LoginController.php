<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use LaravelUi5\Auth\Contracts\LoginSuccessProviderInterface;
use LaravelUi5\Auth\Requests\LoginRequest;

final class LoginController
{
    public function __invoke(
        LoginRequest                  $request,
        LoginSuccessProviderInterface $provider,
    ): JsonResponse {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'message'  => 'login_success',
            'redirect' => $provider->redirectUrl($request),
        ]);
    }
}

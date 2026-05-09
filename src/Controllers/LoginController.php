<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use LaravelUi5\Auth\Contracts\IntentDispenserInterface;
use LaravelUi5\Auth\Requests\LoginRequest;

final class LoginController
{
    public function __invoke(
        LoginRequest             $request,
        IntentDispenserInterface $dispenser,
    ): JsonResponse {
        $request->authenticate();

        $request->session()->regenerate();

        $intent = $dispenser->dispense($request->user(), $request);

        return response()->json([
            'message' => 'login_success',
            'next'    => $intent->serialize(),
        ]);
    }
}

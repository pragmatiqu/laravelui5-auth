<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\JsonResponse;
use LaravelUi5\Auth\Requests\ForgotPasswordRequest;

final class ForgotPasswordController
{
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        $request->sendResetLink();

        return response()->json(['message' => 'reset_link_sent']);
    }
}

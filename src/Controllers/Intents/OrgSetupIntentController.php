<?php

namespace LaravelUi5\Auth\Controllers\Intents;

use Illuminate\Http\JsonResponse;
use LaravelUi5\Auth\Contracts\IntentDispenserInterface;
use LaravelUi5\Auth\Contracts\OrgSetupHandlerInterface;
use LaravelUi5\Auth\Intents\Results\IntentResult;
use LaravelUi5\Auth\Requests\OrgSetupRequest;

final class OrgSetupIntentController
{
    public function __invoke(
        OrgSetupRequest          $request,
        OrgSetupHandlerInterface $handler,
        IntentDispenserInterface $dispenser,
    ): JsonResponse {
        $result = $handler->handle($request->user(), $request);

        $response = [
            'status'    => $result->status,
            'next_step' => $result->nextStep,
            'errors'    => $result->errors,
            'next'      => null,
        ];

        if ($result->status === IntentResult::STATUS_SATISFIED) {
            $response['next'] = $dispenser->dispense($request->user(), $request)->serialize();
        }

        return response()->json($response);
    }
}

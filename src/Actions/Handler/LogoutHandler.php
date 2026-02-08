<?php

namespace Pragmatiqu\Auth\Actions\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use LaravelUi5\Core\Ui5\AbstractConfigurable;
use LaravelUi5\Core\Ui5\Capabilities\ActionHandlerInterface;

class LogoutHandler extends AbstractConfigurable implements ActionHandlerInterface
{
    public function handle(Request $request): array
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $path = route('home');

        return [
            'message' => 'logout_success',
            'redirect' => $path,
        ];
    }
}

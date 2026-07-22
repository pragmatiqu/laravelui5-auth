<?php

namespace LaravelUi5\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use LaravelUi5\Core\Ui5\Contracts\Ui5RegistryInterface;

class LoginRedirectController
{
    /**
     * Redirects to the Auth app.
     *
     * If the client bounced here with a `?redirect=` target — the `SessionGuard`
     * re-auth path on an expired session (a client-initiated `window.location`
     * bounce, so Laravel never set `url.intended` the way `redirect()->guest()`
     * would) — stash it as `url.intended`. The `DefaultIntentDispenser` then
     * resumes there after login, through the same seat a browser-nav bounce uses,
     * with no change to the dispenser or the login submit.
     *
     * Only a **same-origin relative path** is honoured — an open-redirect guard:
     * a query-supplied target must be rooted (`/…`), not protocol-relative
     * (`//host`, `/\host`) and carry no host, or an attacker could bounce an
     * authenticated user off-site via `/login?redirect=https://evil.example`.
     *
     * @param Request              $request
     * @param Ui5RegistryInterface $registry
     * @return RedirectResponse
     */
    public function __invoke(Request $request, Ui5RegistryInterface $registry): RedirectResponse
    {
        $target = $request->string('redirect')->toString();

        $isSameOriginPath = $target !== ''
            && str_starts_with($target, '/')
            && ! str_starts_with($target, '//')
            && ! str_contains($target, '\\')
            && parse_url($target, PHP_URL_HOST) === null;

        if ($isSameOriginPath) {
            $request->session()->put('url.intended', $target);
        }

        return redirect($registry->resolveIndexUrl('com.laravelui5.auth', '/'));
    }
}

# laravelui5/auth

Password + session authentication for [LaravelUi5](https://laravelui5.com) apps, with a UI5 frontend.

It ships a self-contained UI5 login mini-app (login, logout, forgot/reset password) driven through LaravelUi5 Core, on top of Laravel's standard `Auth` and `Password` brokers — plus an **intent system** for sequencing post-login steps (e.g. org onboarding) before the user reaches their destination.

## Requirements

- PHP `>= 8.2`
- `laravelui5/core: ^0.9` (which determines the supported Laravel version)
- A consuming app with Laravel's stock auth tables (`users`, `password_reset_tokens`) and the named host routes the package redirects to — at minimum `home` and `dashboard` (see [What the host must provide](#what-the-host-must-provide)).

## Installation

```bash
composer require laravelui5/auth
```

The service provider (`LaravelUi5\Auth\AuthServiceProvider`) is auto-discovered via `extra.laravel.providers` — no manual registration. On boot it loads the package routes, registers the UI5 `AuthModule` with Core's infrastructure collector, and points Laravel's password-reset notification at the package's `password.reset` route.

## What it provides

Routes (all under the `web` middleware group):

| Method | URI | Name | Notes |
|:---|:---|:---|:---|
| GET | `/login` | `login` | Redirects to the UI5 auth app (guest only) |
| POST | `/auth/login` | `login.submit` | Email + password sign-in, rate-limited |
| POST | `/auth/forgot-password` | `password.email` | Enumeration-safe reset request |
| GET | `/password/reset/{token}` | `password.reset` | Pre-validates the token, then redirects to the UI5 set-password view |
| POST | `/auth/reset-password` | `password.update` | Sets the new password |
| POST | `/logout` | `logout` | Drops the session (auth only) |
| POST | `/auth/intents/org-setup` | `auth.intents.org_setup` | Satisfies the OrgSetup intent (auth only) |

Login is throttled (5 attempts → `Lockout`), the session is regenerated on success, and forgot-password responses are identical whether or not the email exists.

## What the host must provide

The package ships the routes above, but it **redirects to named routes it does not define** — your app must provide them. Two are effectively required:

| Name | Used by | Required? | If missing |
|:---|:---|:---|:---|
| `home` | `LogoutController` — every logout ends with `redirect()->route('home')` | **Yes** | logout throws `RouteNotFoundException` (HTTP 500) |
| `dashboard` | `DefaultIntentDispenser` — the default post-login landing | **Yes**, unless you bind your own `IntentDispenserInterface` that never falls back to it (see [Post-login redirects](#post-login-redirects)) | login throws `RouteNotFoundException` right after credentials validate |

Three further routes are **optional** — the login UI renders a footer link for each only when the route exists (each is guarded by `Route::has()`), so leaving them undefined is safe:

| Name | Footer link |
|:---|:---|
| `terms` | Terms of service |
| `privacy` | Privacy policy |
| `cookies` | Cookie policy |

Minimal host wiring:

```php
// routes/web.php
Route::get('/', HomeController::class)->name('home');   // public; logout lands here

Route::get('/dashboard', /* your post-login landing */)
    ->middleware('auth')                                // guests bounce to /login
    ->name('dashboard');
```

## Post-login redirects

After credentials are validated, the package asks an **`IntentDispenserInterface`** what to do next. It is called once after login and again after each intent is satisfied, until it returns a terminal `RedirectIntent`.

The default, `DefaultIntentDispenser`, returns:

```php
new RedirectIntent(session()->pull('url.intended', route('dashboard')));
```

So out of the box, a user is sent to their intended URL or to `route('dashboard')`. Override by binding your own dispenser:

```php
// In a service provider
$this->app->singleton(
    \LaravelUi5\Auth\Contracts\IntentDispenserInterface::class,
    \App\Auth\MyIntentDispenser::class,
);
```

## Intents (multi-step onboarding)

A dispenser can return non-terminal intents to interrupt login with extra steps. The intent catalog is **closed** — kinds are added to the package deliberately. Two kinds ship today:

- `RedirectIntent { target }` — terminal; sends the user to a URL.
- `OrgSetupIntent { personName }` — prompts the user to complete organisation setup.

`OrgSetupIntent` requires the host to bind an **`OrgSetupHandlerInterface`**; the package ships no default, so dispensing it without a handler fails loud at request time (by design — the step needs host-side state coordination). A handler returns an `IntentResult`: `satisfied()`, `needsMoreInput($payload)`, or `error($errors)`.

## License

MIT — see [LICENSE](LICENSE).

## Changelog

See [CHANGELOG.md](CHANGELOG.md).

---

## About this repository

This repo is the canonical **published distribution** of `laravelui5/auth` — the PHP source here is a faithful, content-identical copy of upstream, so it's the right place to read the code, consume the package, and [file issues](https://github.com/pragmatiqu/laravelui5-auth/issues).

It is **not** where development happens. It's generated by an automated [splitsh-lite](https://github.com/splitsh/lite) sub-split from the LaravelUi5 workspace, and its branch history is **force-replaced on every release** — pull requests opened here won't survive. Two further caveats for would-be contributors:

- The UI5 frontend under `resources/ui5/` is **compiled output**; its editable source lives in a separate project.
- Building from a clean clone requires `laravelui5/core`, which is distributed via a private package registry.

Code changes are integrated upstream, not merged here.

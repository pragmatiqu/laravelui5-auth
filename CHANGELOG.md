# Changelog

All notable changes to `laravelui5/auth` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Entries for `0.1.0`–`0.1.5` predate this file; they were reconstructed from the host
monorepo's git history (tags `auth/v0.1.0`–`auth/v0.1.5`) and summarize the source/i18n
changes per release rather than every commit.

## [0.2.2] - 2026-07-22

Completes the session-expiry **resume** path. When an expired session bounces the client to
`/login?redirect=<path>` (the `ui5-core-lib` `SessionGuard` re-auth path — a client-initiated
`window.location` bounce, so Laravel never set `url.intended` the way `redirect()->guest()`
would), `LoginRedirectController` now stashes that target as Laravel's `url.intended`. The existing
`DefaultIntentDispenser` (`url.intended` → terminal `RedirectIntent`) then resumes there after
login — the same seat a browser-nav bounce already used. **No change to the login submit, the
dispenser, the intent catalog, or the UI5 client.** Requires the paired `SessionGuard` that sends a
navigable `pathname + search + hash` target (`@laravelui5/core` 6.0.3, bundled into Core) — a bare
route hash would resume wrong; ship that bundle with or before this release.

### Fixed

- **`LoginRedirectController` honours a same-origin `?redirect=` target**, stashing it as
  `url.intended`. Open-redirect guarded: only a rooted path (`/…`) with no host and no
  protocol-relative (`//`, `/\`) or backslash form is accepted — a query-supplied
  `https://evil.example` or `//evil.example` is ignored, never followed. Sealed by
  `tests/Feature/Auth/LoginRedirectTest.php` (host).

## [0.2.1] - 2026-07-12

A packaging fix. `0.2.0` shipped **without its compiled frontend** — the runtime `manifest.json`
and most of the `resources/ui5/` bundle were dropped while slimming the package to its runtime
essentials, leaving a UI5 app with no descriptor. Any consumer that loads the Auth app (the
`laravel.ui5` manifest injection, the SDK's ability extraction) failed with *"Manifest not
found."* **No code, contract, route, or namespace change** — this release only restores the
shipped assets. Skip `0.2.0`; adopt `0.2.1`.

### Fixed

- **Restore the shipped frontend, as the runtime set.** `ui5/Auth/resources/ui5/` ships
  `manifest.json`, `Component-preload.js` (+ source map), and the `i18n/*.properties` bundles.
  The sources (controllers, views, `Component.js`, `*-dbg.*`, `*.map`) are intentionally **not**
  shipped — they are concatenated into the preload and never fetched in production (Core's
  `IndexController` boots the app with preload enabled); `index.html` is generated server-side.
  This is leaner than the `0.1.x` line, which over-shipped the sources.
- **Guardrail against recurrence.** Publishing is now deterministic: `npm run publish:host` (in
  the `ui5-auth` source repo) copies exactly the essential set and **aborts if `manifest.json`
  or the preload is missing**, replacing the error-prone "copy the build, then delete the
  sources" step that dropped the manifest.

## [0.2.0] - 2026-07-12

Requires `laravelui5/core` **2.0.0** — the class-string declaration major. Two breaking
changes ride this release: Auth adopts Core 2.0's class-string artifact contract, and the
app's namespace is renamed for brand consistency. The `/login`, `/logout`, and
`/password/reset/{token}` **routes are unchanged** — only the UI5 app-asset URLs move — and
the `@1.0.0` artifact-version coordinate is unchanged.

### Changed

- **Requires `laravelui5/core` `^2.0`** (was `^1.0`). `AuthApp::getLaravelUiManifest()` now
  returns a **class-string** (`return AuthManifest::class;`, return type `: string`) per Core
  2.0's declaration contract — the runtime owns resolution (`app(...)`). This is Auth's whole
  surface under the Core 2.0 break: the module registers only `AuthApp` (every other artifact
  list is empty), so nothing else was touched.
- **App namespace `io.pragmatiqu.auth` → `com.laravelui5.auth`.** Brings the app identity in
  line with the `laravelui5/*` package / `com.laravelui5.*` namespace convention (Core is
  `com.laravelui5.core`); the vendor-flavoured `io.pragmatiqu.*` was the outlier. The compiled
  frontend (`manifest.json` `sap.app.id`, every module path, the i18n bundle name) and the PHP
  side (`AuthApp::NAMESPACE`, both redirect controllers) move together. The app's UI5 assets
  now serve at `/ui5/app/com/laravelui5/auth@1.0.0/…`; the old `/ui5/app/io/pragmatiqu/auth@…`
  URLs 404.
- **`AuthApp::DESCRIPTION`** is now a real sentence ("Email and password sign-in for
  LaravelUi5 apps, with self-service password reset.") instead of the placeholder namespace
  string. `TITLE` remains `Login`.

### Migration

- Bump `laravelui5/auth` to `^0.2` **and** `laravelui5/core` to `^2.0` — the two are coupled.
  `^0.1` will not resolve `0.2.0` (by design: the break is opt-in, so a `composer update`
  won't pull it silently).
- If the consuming app uses the SDK's DB-backed registry, run `php artisan ui5:sync` so the
  `sdk_artifacts` row picks up the new namespace. Auth declares no `#[Access]` abilities, so
  no ability grants are affected.
- No route, controller, or request changes — `/login`, `/logout`, `/password/reset/{token}`
  are stable.

## [0.1.9] - 2026-06-03

Requires `laravelui5/core` **1.0.0** — Auth adopts the Core 1.0 line the day the artifact
surface froze (the `0.9.x` "SemVer credit spent" convention ended at 1.0.0). **No change to
Auth's own contract** — the manifest, routes, and the app's `@1.0.0` artifact-version
coordinate are all unchanged.

### Changed

- **Requires `laravelui5/core` `^1.0`** (was `^0.9.28`).
- **The redirect controllers adopt Core's `resolveIndexUrl()` helper.** `LoginRedirectController`
  and `ResetPasswordRedirectController` replace the hand-built
  `$registry->resolve(…) . '/index.html#/…'` string with
  `$registry->resolveIndexUrl('io.pragmatiqu.auth', $segment)` (Core 1.0), centralizing the
  index-URL + hash-fragment shape. Same destinations, no behavioural change.

## [0.1.8] - 2026-05-27

Requires `laravelui5/core` **0.9.28+** — the release that single-sources the module
namespace (`Ui5ModuleInterface::getName()` → `getNamespace()`), moves the assets
trait to `Ui5\Concerns`, and relocates the UI5 control enums. This release adapts
Auth to those breaking changes. **No change to Auth's own contract** — the
`infra.auth.logout` node (0.1.7), the manifest, the routes, and the app's `@1.0.0`
artifact-version coordinate are all unchanged.

### Changed

- **`AuthApp` adopts Core's const-backed identity.** The four identity getters are
  replaced by `NAMESPACE` / `VERSION` / `TITLE` / `DESCRIPTION` class constants;
  the getters are inherited from Core's `HasArtifactIdentity` (Core 0.9.26). The
  redundant `HasAssetsTrait` use and the `getType()` override are dropped — the
  base `AbstractUi5App` now supplies `HasAssets` (the trait moved to
  `LaravelUi5\Core\Ui5\Concerns\HasAssets` in 0.9.28) and the `Application` type.
  The app title is now `Login` (was the namespace string); the `@1.0.0` version is
  unchanged.
- **`AuthModule` no longer declares its namespace.** `getName()` is removed; the
  module's namespace now derives from its root artifact (`AuthApp::NAMESPACE`)
  via Core's single-source model (0.9.28), so the module and app namespaces can
  never drift.

## [0.1.7] - 2026-05-26

Requires `laravelui5/core` **0.9.21+** — the release that adds
`Ui5InfrastructureContributorInterface` and the `laravel.ui5/infra` manifest node.

### Added

- **The logout route is published as a cross-cutting infrastructure fact.** `AuthModule` now
  implements Core's `Ui5InfrastructureContributorInterface` (infrastructure key `auth`) and
  contributes `laravel.ui5.infra.auth.logout`. Unlike a per-app `routes` entry — which appears
  only in the auth app's own manifest — this node is injected into **every** module's manifest,
  so a consuming shell (e.g. a portal ShellBar) reads the platform logout URL the same way from
  any app, without each module re-declaring it. The contribution is static and user-invariant,
  per Core's `infra` contract.

### Changed (breaking — manifest contract)

- **Logout URL moved from `laravel.ui5.routes.logout` to `laravel.ui5.infra.auth.logout`.**
  `AuthManifest` no longer publishes `logout` under its `routes` fragment. Consumers reading
  `routes.logout` (e.g. a ShellBar logout control) must switch to `infra.auth.logout`. The
  remaining `routes` entries (`logo`/`terms`/`privacy`/`cookies`) are unchanged — those are
  genuinely app-specific and stay on the per-app `routes` fragment.

## [0.1.6] - 2026-05-26

### Fixed

- **Login redirect no longer inherits the client app's hash route.** `LoginRedirectController`
  now sends guests to `…/io.pragmatiqu.auth/index.html#/` with an **explicit** `#/` fragment.

  The bug: a top-level navigation from a hash route in another UI5 app (e.g.
  `…/portal@1.0.0/index.html#/tokens`) landed on the auth app at
  `…/auth@1.0.0/index.html#/tokens` — a route the auth router has no pattern for, so nothing
  rendered. This was **browser fragment inheritance**, not our code copying the hash: the
  fragment is never sent to the server, and per RFC 7231 §7.1.2 + the WHATWG URL/fetch
  standard, when a `302 Location` carries no fragment of its own the browser re-attaches the
  fragment from the page being left. Giving the redirect target its own `#/` fragment
  overrides the inheritance, so the auth app always opens on its default route.

### Note

- **Belt-and-suspenders (not yet implemented):** the auth UI5 router could also self-heal any
  stray/unmatched hash by attaching a `bypassed` handler (or a not-found target) that
  `navTo`s the home route. The redirect-side fix above is the root cause and is sufficient on
  its own; the router catch would make the app robust to *any* inherited hash from a future
  consumer. Revisit if another entry path reintroduces the symptom.

## [0.1.5] - 2026-05-13

### Added

- `billing_email` on the Org Setup flow — new field on `OrgSetupRequest` validation, the
  `OrgSetup` view + controller, and the i18n triplet (default/en/de).

## [0.1.4] - 2026-05-09

### Added

- **Org Setup post-login flow.** New `OrgSetup` UI5 view + controller and an `IntentCatalog`
  model, with the i18n triplet for the flow. First consumer of the intent system shipped in
  0.1.3.

### Changed

- Dropdowns now use string keys instead of indices (`Login` + `OrgSetup` controllers).

## [0.1.3] - 2026-05-09

### Changed

- **Auth intent system replaces the `Ui5Action` dispatch.** Removed the action/handler pairs
  (`LoginAction`/`LogoutAction`/`ForgotPasswordAction`/`ResetPasswordAction` and their
  `Handler/*`) in favor of plain controllers plus an intent layer: `Intent`, `IntentKind`,
  `RedirectIntent`, `OrgSetupIntent`, `IntentResult`, `IntentDispenserInterface` +
  `DefaultIntentDispenser`, `OrgSetupIntentController`, and `OrgSetupRequest`.
- Dropped `LoginSuccessProviderInterface` / `LoginSuccessProvider` (superseded by the intent
  dispenser).

### Added

- `LoginRedirectController` and `ResetPasswordRedirectController` — the server-side entry
  points that bounce guests to the auth app. (`LoginRedirectController` is the file fixed in
  0.1.6.)

## [0.1.2] - 2026-05-05

### Added

- **Password-reset frontend** completing the M-Auth-1 "forgot → email → set password" flow on
  the UI5 side: `ForgotPassword` and `SetPassword` views + controllers (TypeScript), and a
  `LegalFooter` fragment.

### Changed

- Reworked the `Login` view layout.

## [0.1.1] - 2026-04-29

### Added

- **Password-reset MVP (backend, M-Auth-1).** `ResetPasswordAction` + `ResetPasswordHandler`,
  `ForgotPasswordRequest` + `ResetPasswordRequest`, and `ResetPasswordController` with GET-time
  token pre-validation against `Password::broker()`. Forgot/reset routes wired; i18n triplet
  added. Enumeration-safe forgot responses, throttled.

## [0.1.0] - 2026-04-28

### Added

- **Initial release — login + logout foundation (M-Auth-0).** Email+password sign-in over
  Laravel's `Auth::attempt` (rate-limited, session regenerated on success) and logout that
  drops the session, fronted by a UI5 Login mini-app driven through the Core action dispatcher.
- First split-and-tag publish as `laravelui5/auth` (MIT, `laravelui5/core: ^0.9`,
  auto-discovered via `extra.laravel.providers`).

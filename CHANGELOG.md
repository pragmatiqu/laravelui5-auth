# Changelog

All notable changes to `laravelui5/auth` are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Entries for `0.1.0`–`0.1.5` predate this file; they were reconstructed from the host
monorepo's git history (tags `auth/v0.1.0`–`auth/v0.1.5`) and summarize the source/i18n
changes per release rather than every commit.

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

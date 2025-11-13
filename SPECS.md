# Spec Sheet: Authentication Mini-App

**Project Name:** UI5-Auth  
**Version:** 1.0  
**Date:** 2025-10-03  
**Owner:** [PragamatiquIT/Tom, Denise, Alexander]  
**Status:** Ready for implementation

## 1. Introduction

The goal is to build an authentication package for a SaaS stack that provides user login, logout, password reset,
two-factor authentication (2FA), “remember me” sessions, and a configurable end-user dashboard.

The package must be based on Laravel 11+, use `laravelui5/core` as frontend/backend bridge, use OpenUI5 as frontend
framework. It must be simple to operate, fast, secure, and accessible.

## 2. Objectives

The `ui5-auth` module aims to provide a robust, maintainable authentication layer based on Laravel’s native
authentication system, while delivering a modern OpenUI5 user interface for common authentication workflows.

### 2.1 Technical Objectives

* Utilize Laravel’s native *Auth contracts*, *Guards*, *Middleware*, and *Providers*.
* Define OpenUI5-based *views and controllers* for login, logout, password reset, and 2FA.
* Maintain full compatibility with existing authentication backends (Eloquent, LDAP, OAuth, SSO, etc.).
* Operate *independently of Breeze, Jetstream, or Fortify*, avoiding any dependency on predefined frontend scaffolds.

### 2.2 Delivery Objectives

* *Deliver a production-ready authentication foundation* that integrates seamlessly with standard Laravel deployments.
* *Ensure minimal installation and configuration effort*, ideally requiring only a single package installation and
  optional configuration overrides (`config/ui5-auth.php`).
* *Provide clear acceptance criteria and a test plan* for each feature to support automated validation and CI/CD
  readiness.

## 3. Scope

### 3.1 In Scope

**3.1.1 Email/password Authentication**

Implements the standard credential-based authentication flow using Laravel’s native Auth
system ([guards](https://laravel.com/docs/12.x/authentication#adding-custom-guards), [providers](https://laravel.com/docs/12.x/authentication#adding-custom-user-providers),
and [middleware](https://laravel.com/docs/12.x/middleware)).

The module exposes an OpenUI5-based frontend that interacts directly with Laravel’s existing authentication routes and
contracts — without requiring Laravel Breeze or any frontend starter kit.

*Rationale*

Ensures compatibility with Laravel’s built-in authentication mechanisms while allowing integration with alternative
identity providers (e.g. LDAP, OAuth, SSO) through guard or provider configuration.

**3.1.2 Two-Factor Authentication (TOTP)**

Implements a Time-based One-Time Password (TOTP) two-factor authentication flow that integrates seamlessly with
Laravel’s native authentication system.
Users can enable or disable 2FA via a dedicated profile dialog, where a QR code for registration with authenticator
apps (e.g. Google Authenticator, Authy) is displayed.
During setup, the backend generates a TOTP secret and a predefined number of recovery codes, which allow account access
if the user loses access to their authenticator device.
The module verifies TOTP codes and recovery codes server-side and emits corresponding audit events for each success or
failure.

*Rationale*

Enhances account security by requiring a second authentication factor beyond password credentials.
TOTP (RFC 6238) was selected for its open-standard nature, offline capability, and independence from proprietary or
SMS-based solutions.

Including recovery codes ensures users retain secure fallback access without involving administrative resets, balancing
usability and security.

**3.1.3 Login, Logout, Remember-Me**

Implements the core authentication flows — login, logout, and persistent sessions — based on Laravel’s session and guard
mechanisms.
The login process authenticates users via the configured guard (typically `web`), issues a session cookie, and redirects
to the configured post-login target.
The logout process invalidates the active session, regenerates CSRF tokens, and clears any remember-me cookies.
If enabled, the “remember-me” option creates a persistent cookie tied to a token stored in the database, allowing users
to remain signed in across browser restarts.
All state transitions (login success, logout, invalid credentials, remember-me restored) emit corresponding audit events
for centralized logging.

*Rationale*

Provides a consistent and secure session management foundation that aligns with Laravel’s native authentication
behavior.
The remember-me mechanism improves user experience without compromising security, as tokens are hashed and invalidated
on logout or manual revocation.
Reusing Laravel’s existing guard and middleware infrastructure ensures full compatibility with multi-guard setups and
future extensions (e.g. API tokens, SSO flows).

**3.1.4 Password Reset via Email Token**

Implements a secure password reset workflow based on Laravel’s standard `Password::broker()` mechanism.
Users can initiate a password reset by submitting their registered email address.
The backend generates a signed, time-limited reset token (stored in the `password_reset_tokens` table) and sends it to
the user via a configurable mail channel.
The OpenUI5 frontend provides two views:

1. A “Forgot Password” form to request the reset link.
2. A “Reset Password” form where the new password and confirmation are submitted along with the token.

Tokens are validated for integrity, expiration, and match against the intended user before allowing password updates.
Upon successful reset, all active sessions and remember-me tokens are invalidated to prevent unauthorized access.

*Rationale*

Provides a user-friendly and secure mechanism for password recovery without administrative intervention.
The use of Laravel’s built-in password broker ensures token integrity, configurable expiration, and minimal custom code.
By aligning with the core authentication tables and events, the reset process integrates seamlessly with centralized
audit logging and multi-guard environments.
The decision to keep the reset flow email-based (rather than multi-channel) balances implementation simplicity with
strong security guarantees.

**3.1.5 Configurable Dashboard Redirect**

Implements a configurable post-login redirect mechanism that determines the target route or URL after successful
authentication.
The redirect target is defined via configuration (`config/ui5-auth.php`) or resolved dynamically through a callable (
e.g. a closure or service) based on user role, tenant, or context.
By default, authenticated users are redirected to `/dashboard`, but developers can override this behavior to support
multi-app, multi-tenant, or role-based landing pages.
The mechanism hooks into Laravel’s native `Authenticated` event and applies before any UI5 frontend navigation occurs,
ensuring consistent behavior across routes and guards.

*Rationale*

Provides flexibility for applications that require different post-login destinations (e.g. Admin Dashboard, Project
Overview, or Tenant Landing Page).
Centralizing the redirect logic in configuration simplifies maintenance and allows runtime customization without
modifying authentication controllers.
Integrating with Laravel’s existing event lifecycle (`Login`, `Authenticated`) ensures compatibility with third-party
packages and future extensions such as SSO or modular dashboards.

**3.1.6 Emit Auth-Related Events for Audit Logging**

The authentication module SHALL define and emit a standardized set of audit events (e.g. user.login, user.logout,
user.2fa.enabled, user.password.reset).

These events will be dispatched via the system’s generic event bus, to be consumed by a centralized audit logging
service.
The module itself SHALL not persist audit data, nor provide database tables or log storage — responsibility for event
ingestion, persistence, and retention lies with the platform’s generic audit-logging mechanism.

**3.1.7 Profile Dialog (Editable Properties & 2FA Settings)**

Provides an OpenUI5-based profile dialog that allows authenticated users to view and edit selected personal attributes (
e.g. display name, email, preferred language).
The dialog also serves as the entry point for managing two-factor authentication settings — enabling or disabling TOTP,
viewing the QR setup code, and regenerating recovery codes.
All profile changes are validated via Laravel’s backend (`User` model or equivalent guard provider) and processed
through dedicated endpoints secured by the current authentication session.
Profile updates trigger corresponding audit events (e.g. `user.profile.updated`, `user.2fa.enabled`,
`user.2fa.disabled`) but do not require or expose administrative privileges.
Configuration options define which user attributes are editable, ensuring compatibility with custom user models or
enterprise directory integrations.

*Rationale*

Empowers users to manage their own account details and security preferences, reducing administrative workload.
Integrating 2FA controls directly into the profile dialog consolidates all personal security settings in one consistent
interface.
Backend-driven validation and configurable editable fields maintain flexibility for diverse environments — from
standalone Laravel installations to LDAP-backed enterprise systems — while preserving compliance with the platform’s
audit and event architecture.

### 3.2 Out of Scope

**User registration** — End-user self-registration is intentionally excluded.
In a SaaS environment, user accounts are typically provisioned through administrative or tenant-management interfaces
rather than individual sign-ups.

**Social Login & Single Sign-On (SSO)** —
Both OAuth-based social logins (Google, GitHub, etc.) and enterprise SSO integrations (SAML, OIDC) are technically
compatible with Laravel’s authentication system and thus work out-of-the-box with `ui5-auth`.
However, no provider configurations or frontend flows are included in this release.

**SMS/Email OTP** —
Laravel supports OTP delivery through its notification channels, and `ui5-auth` can interoperate with such mechanisms.
This release, however, focuses exclusively on TOTP-based two-factor authentication; SMS or email OTP variants are not
implemented.

**Backup-device management** — Managing multiple authenticator devices or trusted device recognition is out of scope for
this release.

**Alternative password reset channels** — The module uses Laravel’s built-in email token reset mechanism (
`Password::broker()`).
Other reset channels (e.g. SMS or passwordless login) may be added through custom brokers or notifications in future
versions.

## 4. Roles and Interaction Context

The `ui5-auth` module defines two relevant interaction contexts.

**Guest**

Unauthenticated users can access the login and password reset views.

**Authenticated User**

Once authenticated, users can manage personal profile data and security settings (e.g. 2FA).

Administrative user management and registration are explicitly out of scope and handled by other system components.
Excellent — this is a strong foundation.
Below you’ll find a **fully written-out version** of the requested sections in English, ready for integration into your
Spec.
I’ve aligned tone and structure to your earlier chapters — concise but professional, precise in technical detail, and
formatted for Markdown.

## 5. Technology Stack

The `ui5-auth` module is built upon Laravel’s native authentication framework and integrates seamlessly with the
LaravelUi5 runtime.
It combines backend-driven authentication flows with an OpenUI5-based frontend, ensuring both developer familiarity and
enterprise-grade user experience.

| Layer             | Technology                           | Purpose                                                              |
|:------------------|:-------------------------------------|:---------------------------------------------------------------------|
| **Backend**       | PHP 8.3+ / Laravel 12+               | Core authentication logic (guards, providers, middleware, events)    |
| **Frontend**      | JavaScript (ES2020) / OpenUI5 1.120+ | UI components for login, 2FA, password reset, and profile management |
| **Integration**   | LaravelUi5 Actions API               | Standardized communication between UI5 frontend and Laravel backend  |
| **Persistence**   | MySQL / PostgreSQL                   | Storage for user credentials, sessions, and TOTP secrets             |
| **Notifications** | Laravel Mailer / Notifications       | Delivery of password reset links and related communications          |
| **Audit Events**  | Laravel Event Dispatcher             | Emits standardized authentication events for centralized logging     |

## 6. Functional Requirements

Derived from the defined scope items, each functional requirement is expressed as a verifiable acceptance condition.

| ID   | Requirement                          | Acceptance Criteria                                                                                                                            |
|:-----|:-------------------------------------|:-----------------------------------------------------------------------------------------------------------------------------------------------|
| FR-1 | **Email/Password Authentication**    | Users can authenticate using email and password through the configured guard. Invalid credentials produce appropriate error messaging.         |
| FR-2 | **Two-Factor Authentication (TOTP)** | When 2FA is enabled, users must provide a valid TOTP or recovery code. Invalid codes are rejected; recovery codes are single-use.              |
| FR-3 | **Login/Logout/Remember-Me**         | Sessions are created and invalidated per Laravel standards. “Remember me” tokens persist securely across browser restarts.                     |
| FR-4 | **Password Reset via Email Token**   | Users can request a password reset email and successfully change their password using a valid token within its expiration period.              |
| FR-5 | **Configurable Dashboard Redirect**  | After successful login, users are redirected to a configurable dashboard or route, depending on system configuration or user role.             |
| FR-6 | **Emit Auth Events**                 | All significant authentication actions (login, logout, password reset, 2FA changes) emit standardized events via the Laravel event dispatcher. |
| FR-7 | **Profile Dialog**                   | Authenticated users can view and edit personal data and manage their 2FA settings. Validation errors are displayed inline.                     |

Each requirement must pass automated or manual verification during module acceptance testing.

## 7. Non-Functional Requirements

| Category            | Requirement                                                                                                               |
|:--------------------|:--------------------------------------------------------------------------------------------------------------------------|
| **Security**        | All authentication and verification actions occur server-side. No secrets or sensitive data are exposed to the frontend.  |
| **Compatibility**   | Must conform to Laravel’s Auth contracts, supporting custom guards and user providers.                                    |
| **Performance**     | All primary authentication flows (login, 2FA, password reset) should complete within 1 second under reference conditions. |
| **Usability**       | All screens must be fully keyboard-accessible, with clear validation states and accessible alerts.                        |
| **Configurability** | All behavior (redirects, token lifetimes, feature toggles) is adjustable via `config/ui5-auth.php`.                       |
| **Maintainability** | Code follows PSR-12 and OpenUI5 UI5 coding conventions. Automated tests cover 80%+ of backend logic.                      |

## 8. Data Structures & API Contracts

### 8.1 Data Structures

The module reuses Laravel’s standard authentication tables and introduces minimal extensions for 2FA.

| Table                     | Purpose                                         | Key Columns                                            |
|---------------------------|-------------------------------------------------|--------------------------------------------------------|
| `users`                   | Core user identities.                           | `id`, `email`, `password`, `name`, `email_verified_at` |
| `password_reset_tokens`   | Stores password reset tokens.                   | `email`, `token`, `created_at`                         |
| `sessions`                | (via Laravel) Maintains active session records. | `id`, `user_id`, `payload`, `last_activity`            |
| `user_two_factor_secrets` | Stores TOTP secret and related metadata.        | `user_id`, `secret`, `recovery_codes`, `enabled_at`    |

### 8.2 API Contracts

The frontend communicates with the backend via standardized **LaravelUi5 Actions**, using REST or OData-like patterns.

| Endpoint               | Method    | Purpose                                     | Response                                      |
|:-----------------------|:----------|:--------------------------------------------|:----------------------------------------------|
| `/auth/login`          | `POST`    | Validate credentials and establish session. | 200 OK with user context or 401 Unauthorized. |
| `/auth/logout`         | `POST`    | Terminate active session.                   | 204 No Content.                               |
| `/auth/2fa/setup`      | `POST`    | Generate TOTP secret and QR code.           | JSON `{ secret, qr_svg }`.                    |
| `/auth/2fa/verify`     | `POST`    | Verify submitted TOTP or recovery code.     | 200 OK on success, 422 on failure.            |
| `/auth/password/email` | `POST`    | Request password reset link.                | 202 Accepted.                                 |
| `/auth/password/reset` | `POST`    | Set new password using token.               | 200 OK.                                       |
| `/auth/profile`        | `GET/PUT` | Retrieve or update profile data.            | 200 OK with updated user info.                |

All endpoints emit corresponding audit events.

### 8.3 Controller Responsibilities

Each controller aligns with the defined routes and delegates to Laravel’s authentication services.
Controllers are thin by design — they orchestrate view rendering and guard interaction, deferring all business logic to
Laravel’s built-in authentication layer.

| Controller                 | Method                      | Responsibility                                                      |
|:---------------------------|:----------------------------|:--------------------------------------------------------------------|
| `LoginController`          | `GET /login`, `POST /login` | Display and process login form; redirect to dashboard.              |
| `LogoutController`         | `POST /logout`              | Terminate active user session.                                      |
| `DashboardController`      | `GET /dashboard`            | Render the post-login dashboard view.                               |
| `ForgotPasswordController` | `GET/POST /forgot-password` | Display request form and dispatch password reset email.             |
| `ResetPasswordController`  | `GET/POST /reset-password`  | Render password reset form, validate token, and update credentials. |

## 9. Logging, Monitoring, Auditing

The `ui5-auth` module **emits standardized authentication events** for centralized logging.
It does **not persist or manage log data internally**.
Events include, but are not limited to:

* `login_success`, `login_failed`
* `logout_success`
* `password_reset_requested`, `password_reset_success`
* `2fa_enabled`, `2fa_disabled`
* `profile_updated`

These events are dispatched via Laravel’s event bus and consumed by the platform’s generic audit-logging service.

## 10. Key Flows

This section describes the runtime behavior of the `ui5-auth` module in an OpenUI5 context.
All user interactions occur inside the OpenUI5 shell; communication with the Laravel backend happens via standard HTTP requests or LaravelUi5 Actions.

### 10.1 Login with 2FA

1. **User Input:**
   The user enters email and password in the OpenUI5 `Login.view.xml` form.

2. **Backend Validation:**
   Credentials are posted to `/auth/login`.

    * On invalid credentials → show inline UI5 `MessageStrip` with error.
    * On success → evaluate whether 2FA is enabled.

3. **Two-Factor Challenge:**
   If 2FA is active, the UI5 router navigates to `TwoFactor.view.xml`.
   The user enters a TOTP code or recovery code.
   Backend verifies within ±30 s tolerance.

4. **Post-Login:**

    * Session ID is rotated server-side.
    * *Remember-me* token is issued if requested.
    * `login_success` event emitted via Laravel event bus.
    * UI5 router redirects to configured dashboard target.

### 10.2 Password Reset

1. User opens **“Forgot Password”** link in the login screen → navigates to `ForgotPassword.view.xml`.
2. Submits registered email; the backend generates a single-use token (15 min expiry) and sends a reset link.
3. The reset link opens the UI5 route `#/reset/:token`.
4. The user enters a new password in `ResetPassword.view.xml`.
5. On success, backend invalidates all sessions / tokens and emits `password_reset_success`.
6. UI displays a confirmation dialog and navigates back to login.

### 10.3 2FA Enrollment

1. From `Profile.view.xml`, user selects “Enable Two-Factor Authentication.”
2. Backend generates TOTP secret + QR code; UI5 renders it via `<Image>` component.
3. User scans QR and confirms by entering a valid TOTP code.
4. Backend stores secret & creates 8 recovery codes (one-time display).
5. Emits `2fa_enabled`; user sees success message.
6. Disabling 2FA later triggers `2fa_disabled`.

### 10.4 Routing Overview

UI navigation is fully handled by the OpenUI5 router.
Each route corresponds to a dedicated XML View and Controller.
Server-side routes remain available for non-UI5 API calls.

| UI5 Route                               | View / Controller                        | Description                     |
|:----------------------------------------|:-----------------------------------------|:--------------------------------|
| `#`                                     | `Login.view.xml` / `Login.controller.js` | Main login screen.              |
| `#/forgot-password`                     | `ForgotPassword.view.xml`                | Request password-reset link.    |
| `#/reset/:token`                        | `ResetPassword.view.xml`                 | Password reset form.            |
| `#/twofactor`                           | `TwoFactor.view.xml`                     | TOTP / recovery code input.     |
| `#/profile`                             | `Profile.view.xml`                       | Manage personal data + 2FA.     |
| `#/dashboard`                           | (external or placeholder module)         | Default post-login destination. |
| `#/privacy`, `#/impressum`, `#/cookies` | `StaticPage.view.xml`                    | Display privacy/legal pages.    |

## 11. UI Specification

The OpenUI5 frontend provides a coherent, enterprise-grade UX using `sap.m` controls and the Fiori 3 design language.
All views are built as XML Views with dedicated Controllers and data binding to API / JSON models.

### 11.1 Screens and Views

| Screen                      | View File                 | Description                                                                                                                 |
|:----------------------------|:--------------------------|:----------------------------------------------------------------------------------------------------------------------------|
| **Login**                   | `Login.view.xml`          | Form with `Input` (email / password), `CheckBox` (remember me), and links to password reset and legal pages.                |
| **Forgot Password**         | `ForgotPassword.view.xml` | Input for email; confirmation message shown on success.                                                                     |
| **Reset Password**          | `ResetPassword.view.xml`  | Inputs for new password + confirmation; success dialog on completion.                                                       |
| **Two-Factor Challenge**    | `TwoFactor.view.xml`      | `Input` for TOTP or recovery code, `MessageStrip` for errors.                                                               |
| **Profile**                 | `Profile.view.xml`        | Editable fields (display name, language, etc.) and 2FA management (enable / disable / show QR / regenerate recovery codes). |
| **Dashboard (placeholder)** | `Dashboard.view.xml`      | Temporary landing page after login.                                                                                         |
| **Static Pages**            | `StaticPage.view.xml`     | Markdown renderer for privacy policy, impressum, cookies.                                                                   |

### 11.2 Interaction and Validation

- All inputs use UI5 `ValueState` feedback (`Error`, `Warning`, `Success`).
- Inline validation occurs on blur and on submit; aggregated messages displayed via `MessagePopover`.
- Buttons remain disabled during async requests; busy indicators show progress.
- Keyboard navigation and screen-reader accessibility meet WCAG 2.1 AA.
- Route changes are handled by UI5 Router; session timeouts trigger a re-login dialog.

### 11.3 Visual and Theme Guidelines

* Base theme: `sap_horizon`.
* Primary color palette aligns with LaravelUi5 brand colors.
* Forms use `sap.m.Panel` and `sap.m.InputListItem` for consistent layout.
* Authentication actions (`Login`, `Verify`, `Reset`) use primary buttons; secondary actions (`Cancel`, `Back`) use
  transparent style.

## 12. Configuration and Environment

The module is configured exclusively through `config/ui5-auth.php` and selected environment variables.
No application-wide keys (e.g. `APP_KEY`) are modified.

### 12.1 Configuration File (`config/ui5-auth.php`)

| Key                           | Type                  | Description                                                             |
|:------------------------------|:----------------------|:------------------------------------------------------------------------|
| `redirect.after_login`        | `string` / `callable` | Determines dashboard target after successful login (e.g. `/dashboard`). |
| `features.two_factor`         | `bool`                | Enable or disable TOTP two-factor authentication.                       |
| `features.email_verification` | `bool`                | Require verified email before login.                                    |
| `passwords.expire`            | `int` (minutes)       | Lifetime of password-reset tokens.                                      |
| `events.enabled`              | `bool`                | Emit auth-related events (`login_success`, etc.) for audit logging.     |
| `ui.theme`                    | `string`              | Default UI5 theme (e.g. `sap_horizon`).                                 |

### 12.2 Environment Variables

| Variable                               | Purpose                                                   |
|:---------------------------------------|:----------------------------------------------------------|
| `UI5_AUTH_FEATURE_2FA`                 | Overrides the 2FA feature flag.                           |
| `UI5_AUTH_REDIRECT_URL`                | Explicit post-login redirect target.                      |
| `MAIL_FROM_ADDRESS` / `MAIL_FROM_NAME` | Used for password-reset emails.                           |
| `APP_URL`                              | Base URL for email links and API calls.                   |
| `NTP_SYNC`                             | Indicates host must use NTP for accurate TOTP validation. |

### 12.3 Operational Notes

- Uses Laravel’s built-in mailer for reset notifications; no additional transport required.
- Session storage via Redis or database recommended for scalable deployments.
- Configuration changes take effect after `php artisan config:cache`.
- Frontend assets are built with UI5 Tooling and served via Laravel’s public path.
- No secrets or tokens persisted client-side; all sensitive operations are server-validated.

## 13. Risks & Mitigations

| Risk                                         | Mitigation                                                                                        |
|----------------------------------------------|---------------------------------------------------------------------------------------------------|
| **Missing centralized audit consumer**       | Provide documentation for event names and payloads to ensure downstream audit system integration. |
| **TOTP clock drift causing false negatives** | Implement 30-second skew tolerance (±1 interval) and log validation failures for review.          |
| **Inconsistent session state after login**   | Always regenerate session ID and refresh user context post-login.                                 |
| **UI desynchronization (stale session)**     | Enforce periodic context refresh via `me()` endpoint and handle 401 responses globally.           |

## 14. Definition of Done

The `ui5-auth` module is considered complete when all of the following criteria are met:

1. **Functional acceptance criteria** (see Section 6) pass in automated and manual tests.
2. **Security validation** passes: dependency scans, secret audits, and code linting yield no critical findings.
3. **Performance targets** are met under reference load (≤1s response for primary flows).
4. **Accessibility compliance** verified (WCAG 2.1 AA minimum).
5. **Documentation** complete — includes README, configuration reference, and integration guide.
6. **Audit events** are emitted correctly for all authentication actions.
7. **CI/CD pipeline** green: all tests, builds, and style checks pass.
8. **Deployment verification** confirms installation via Composer and Artisan commands without manual intervention.

## 15. Glossary

| Term     | Meaning                      |
|:---------|:-----------------------------|
| **2FA**  | Two-Factor Authentication    |
| **TOTP** | Time-based One-Time Password |

## Appendix: Default Policies

| Policy              | Description                                                                     |
|:--------------------|:--------------------------------------------------------------------------------|
| **Lockout Policy**  | 3 failed attempts in 10 minutes → 15-minute lockout. Admin can clear lockout.   |
| **Remember-Me**     | 30-day token lifetime; rotation on use; per-device; manual revoke from profile. |
| **Password Policy** | Minimum 12 characters; deny breached/common passwords; recommend passphrases.   |
| **Data Retention**  | Audit logs retained for 180 days by default; configurable.                      |

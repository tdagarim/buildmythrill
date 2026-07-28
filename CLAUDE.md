# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

Laravel 13 + Fortify (auth) + Inertia.js v3 + Vue 3 + Tailwind CSS v4 + Vite 8. PHP 8.3+. MySQL 8.4 + Redis via Laravel Sail (Docker). Pinia for client state, GSAP for animation, Postmark for transactional mail.

This project was scaffolded from Laravel's official Vue starter kit (`laravel new --vue`), which also ships reka-ui components, Wayfinder (typed route helpers for JS), and passkey/2FA auth out of the box.

## Commands

All PHP/Node tooling runs inside the Sail container — the host's Node install is not usable from this WSL environment, so never run `npm` on the host.

```bash
# Start the stack (Laravel app on :80, Vite on :5173, MySQL on :3306, Redis on :6379)
docker compose up -d
docker compose down

# Shell into the app container
./vendor/bin/sail shell

# Any artisan/composer/npm command — prefix with sail
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan tinker
./vendor/bin/sail composer lint          # Pint
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev            # Vite dev server
./vendor/bin/sail npm run build
./vendor/bin/sail npm run lint:check     # eslint
./vendor/bin/sail npm run types:check    # vue-tsc --noEmit
./vendor/bin/sail composer test          # phpunit
```

## Architecture

**Inertia.js bridge**: Laravel routes render Inertia pages (see `routes/web.php`, e.g. `Route::inertia('/', 'Welcome')`). The single Blade entry point is `resources/views/app.blade.php`. Inertia resolves page names to `resources/js/pages/**/*.vue`.

**App bootstrap** (`resources/js/app.ts`): Inertia v3's `createInertiaApp` no longer needs an explicit `resolve`/`setup` pair — page resolution is automatic, and the `withApp` callback is where the Vue app instance is extended. Pinia is registered there via `withApp: (app) => app.use(createPinia())`. Add other global Vue plugins the same way.

**Shared data**: Global Inertia props live in `app/Http/Middleware/HandleInertiaRequests.php` → `share()`.

**Wayfinder**: `@laravel/vite-plugin-wayfinder` generates typed route/controller call helpers under `resources/js/actions` and `resources/js/routes` at build time — prefer these over hardcoded URL strings in Vue components.

**Frontend structure**:
- `resources/js/pages/` — Inertia page components
- `resources/js/layouts/` — `AppLayout.vue` (authenticated), `AuthLayout.vue` (login/register), `settings/Layout.vue`
- `resources/js/components/` — reusable UI, including reka-ui-based primitives under `components/ui/`
- `resources/js/components/AppLogo.vue` / `AppLogoIcon.vue` — Build My Thrill brand mark, inlined as SVG with `fill: currentColor` so it tints via Tailwind text-color classes. Source file: `resources/images/bmt-logo.svg` (also copied to `public/logo.svg`).

**State**: Use Pinia stores under `resources/js/stores/` for state shared across components beyond a single page's Inertia props.

**Animation**: GSAP is a dependency but not globally wired — import it directly in components that need it (`import gsap from 'gsap'`).

**Mail**: Postmark is the configured mail transport (`config/mail.php` → `postmark`, backed by `symfony/postmark-mailer`). Set `POSTMARK_API_KEY` in `.env` (see `config/services.php`) and `MAIL_MAILER=postmark` to send through it; local `.env` defaults to `MAIL_MAILER=log` since no token is configured for local dev.

**Auth**: Fortify-based (not Jetstream) — registration/login/2FA/passkeys live under `resources/js/pages/auth/` and `app/Actions/Fortify/`.

**Database**: MySQL via Sail (`compose.yaml`), not SQLite. `DB_HOST=mysql` inside `.env` refers to the Docker service name, not localhost.

## Docker known issues

- First `sail:install` build is slow (~30 min) — it compiles a full Ubuntu 24.04 image with PHP 8.5 and every extension from source-ish apt packages. Don't assume a stalled-looking build is hung; check `docker buildx du` / container creation before killing it.
- `WWWUSER`/`WWWGROUP` must be set in `.env` (host uid/gid, typically `1000`/`1000`) or Sail falls back to blank, which can cause file-permission mismatches between host and container.

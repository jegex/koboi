# Regression gate (HTTP seam)

The whole Svelte rewrite is verified against an HTTP-seam regression gate. It
locks the server-side contract that the frontend depends on, so every later
milestone must keep it green.

## What it locks

Pest suite under `tests/` (run `composer test`), covering:

- `nova-api` route registration (`api.php`, `asset.php`) under the koboi
  service provider.
- Nova middleware aliases (`nova.guest`, `nova.auth`) and groups
  (`nova`, `nova:api`, `nova:asset`, `nova:serving`).
- Inertia root view `nova::layout` + shared props (`novaConfig`,
  `currentUser`, `validLicense`).
- `Nova::jsonVariables()` — the exact config keys the Svelte client reads.
- Serving events: `ServingNova` and `NovaServiceProviderRegistered` dispatch,
  `NovaRequest` container binding.
- Identity: `Nova::version()` returns the Nova version under the
  `Jegex\Koboi` namespace.

## Key constraint

The gate must stay **server-side and deterministic**. It must not depend on a
Vue/Svelte build or on ChomeDriver, because the frontend is rewritten
incrementally. Dusk/PHPUnit browser coverage is intentionally deferred until
the frontend milestone lands (was issue #3's original scope).

## Truth keeping

When a milestone changes the HTTP seam (routes, middleware, Inertia props,
`jsonVariables`), update this gate in the same commit. Red = seal broken.
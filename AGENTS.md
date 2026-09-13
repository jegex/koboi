## Agent skills

### Issue tracker

Issues and specs for this repo live as GitHub issues (`gh` CLI). See `docs/agents/issue-tracker.md`.

### Triage labels

Five canonical labels: needs-triage, needs-info, ready-for-agent, ready-for-human, wontfix. See `docs/agents/triage-labels.md`.

### Domain docs

Single-context layout — `CONTEXT.md` + `docs/adr/` at the repo root. See `docs/agents/domain.md`.

### Regression gate

The HTTP seam (routes, middleware, Inertia props, `jsonVariables`) is locked by the Pest suite — `composer test` must stay green through every frontend milestone. See `docs/agents/regression-gate.md`.
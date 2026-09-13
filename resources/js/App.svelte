<script>
    let { config = {} } = $props()
</script>

<svelte:head>
    <title>Koboi</title>
</svelte:head>

<div class="app-shell" data-testid="app-shell">
    <h1>Koboi — Svelte 5 admin panel</h1>
    <p class="app-version">Booting version {config?.version}</p>
</div>

<style>
    .app-shell {
        padding: 3rem;
        text-align: center;
        font-family: ui-sans-serif, system-ui, sans-serif;
    }

    .app-version {
        color: #6b7280;
    }
</style>
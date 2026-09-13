<script>
  let { config = Nova.appConfig } = $props()

  let appName = config?.appName ?? 'Nova'
</script>

<svelte:head>
  <title>{appName}</title>
</svelte:head>

<div class="nova-loading" data-testid="nova-loading">
  <p>Loading {appName}...</p>
</div>

<style>
  .nova-loading {
    display: flex;
    min-height: 50vh;
    align-items: center;
    justify-content: center;
  }
</style>

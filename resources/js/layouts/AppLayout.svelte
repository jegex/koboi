<script>
  let { component, props = {}, children } = $props()
</script>

<div id="nova" data-testid="nova-app">
  <!-- Content -->
  <div dusk="content" data-testid="nova-content">
    {#if component}
      <svelte:component this={component} {...props} />
    {:else if children}
      {@render children()}
    {/if}
  </div>
</div>
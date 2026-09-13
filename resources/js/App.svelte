<script>
  import { onMount } from 'svelte'
  import { provideNova, provideNovaConfig } from './context/nova.js'
  import { useNovaConfig } from './context/nova.js'

  let { nova = null, config = null, field = null } = $props()

  /**
   * Bootstrap the Nova runtime as a Svelte context branch so every descendant
   * consumer can resolve Nova primitives via useNova()/useNovaConfig() without
   * a global dependency. Runs during component initialisation — the only phase
   * in which setContext() is legal.
   */
  provideNova(nova ?? globalThis.Nova ?? null)
  provideNovaConfig(config)

  let version = useNovaConfig('version') ?? 'unknown'
</script>

<svelte:head>
  <title>Koboi</title>
</svelte:head>

<div class="koboi-app" data-testid="koboi-app">
  <form action="" layout="inline" dusk="inline-resource-form" data-testid="nova-inline">
    <div dusk="inline-resource-form-layout">
      <span dusk="version" data-testid="nova-version">{version}</span>
    </div>
  </form>

  <slot />
</div>

<style>
  .koboi-app {
    min-height: 100vh;
  }
</style>

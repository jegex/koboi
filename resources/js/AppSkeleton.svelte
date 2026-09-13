<script>
  import { onMount } from 'svelte'
  import { provideNova } from './context/nova.js'
  import { provideNovaConfig } from './context/novaConfig.js'
  import { provideField } from './context/field.js'
  import { useNovaConfig } from './context/nova.js'

  let { nova = null, config = null, field = null } = $props()

  // At the very top of the tree: expose the Nova runtime + its config so every
  // descendant can resolve Nova primitives with useNova()/useNovaConfig()
  // instead of relying on a global. Guarded so unset props never leak.
  if (nova) {
    provideNova(nova)

    if (config) {
      provideNovaConfig(config)
    }
  }

  if (field) {
    provideField(field)
  }

  let version = useNovaConfig('version')

  const handleKeydown = event => {
    if (event.key === 'Escape') {
      console.debug('[koboi] escape pressed')
    }
  }

  onMount(() => {
    window.addEventListener('keydown', handleKeydown)

    return () => {
      window.removeEventListener('keydown', handleKeydown)
    }
  })
</script>

<svelte:head>
  <title>Koboi</title>
</svelte:head>

<div class="koboi-app" data-testid="koboi-app">
  {#if nova}
    <p dusk="nova-version" data-testid="nova-version">{version ?? 'unknown'}</p>
  {/if}

  <slot />
</div>

<style>
  .koboi-app {
    min-height: 100vh;
  }
</style>

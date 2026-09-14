<script>
  /* Modal.svelte — Nova modal. closesOnEscape + trapsFocus; backdrop click
     closes; announces as a dialog to AT. */
  import { closesOnEscape, trapsFocus } from '../actions/index.js'
  let {
    open = false,
    title = null,
    size = 'md',
    className = '',
    onclose = null,
  } = $props()
  let overlaid = $state(false)
  function close() { onclose?.() }
  const maxW = { sm: 'sm:max-w-sm', md: 'sm:max-w-md', lg: 'sm:max-w-lg' }
</script>

{#if open}
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    data-testid="nova-modal"
  >
    <div class="absolute inset-0 bg-gray-950/40" onclick={close} data-testid="nova-modal-backdrop" />
    <div
      bind:this={overlaid}
      role="dialog"
      aria-modal="true"
      aria-labelledby={title !== null ? 'nova-modal-title' : undefined}
      use:closesOnEscape={close}
      use:trapsFocus
      class={`relative w-full rounded-lg bg-white p-6 shadow-xl sm:mx-auto ${maxW[size] ?? maxW.md} ${className}`}
      data-testid="nova-modal-panel"
    >
      {#if title !== null}<h2 id="nova-modal-title" class="text-base font-semibold text-gray-900">{title}</h2>{/if}
      <slot />
    </div>
  </div>
{/if}

<svelte:window onkeydown={ (e) => { if (e.key === 'Escape') onclose?.() } } />

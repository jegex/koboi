<script>
  /* Dropdown.svelte — Nova dropdown/actions. UNCONTROLLED: internal isOpen
     drives the panel; every change props back via onchange so Nova can
     reflect state. Window-level Escape (keydown) + outside-click close. */
  let { label = 'Actions', open = false, onchange = null, onSelect = null, options = [], className = '' } = $props()
  let isOpen = $state(open)
  let root = $state()

  function close() { isOpen = false; onchange?.(false) }
  function onKeyPressedOutside(e) { if (isOpen && root && !root.contains(e.target)) close() }
  function onWindowKey(e) { if (e.key === 'Escape') close() }
</script>

<svelte:window onclick={onKeyPressedOutside} onkeydown={onWindowKey} />

<div bind:this={root} class={`relative inline-block ${className}`} data-testid="nova-dropdown">
  <button
    type="button"
    data-testid="nova-dropdown-trigger"
    onclick={() => { isOpen = !isOpen; onchange?.(isOpen) }}
    class="inline-flex items-center gap-1 rounded-md border border-gray-300 bg-white px-2.5 py-1.5 text-sm text-gray-700 hover:bg-gray-50"
  >
    {label}
    <svg class="ml-1 h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5 8l5 5 5-5" stroke-linecap="round" stroke-linejoin="round"></path></svg>
  </button>

  {#if isOpen}
    <div data-testid="nova-dropdown-panel" role="menu" class="absolute left-0 z-10 mt-1 w-40 rounded-md border border-gray-200 bg-white py-1 shadow-lg">
      {#each options as option (option.value)}
        <button type="button" role="menuitem" data-testid="nova-dropdown-option" onclick={() => onSelect?.(option)} class="block w-full px-3 py-1.5 text-left text-sm text-gray-700 hover:bg-gray-50">
          {option.label}
        </button>
      {/each}
    </div>
  {/if}
</div>

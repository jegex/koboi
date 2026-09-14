<script>
  /* CommandPalette.svelte — AC#2 global search. Auth-bound: opens from the
     AppLayout topbar search trigger; filters the resource definitions by the
     lookup query; Enter navigates to the selected resource.index route.
     `onclose` is required by AppLayout; Escape/backdrop/closing closes it. */
  import { createNovaStore } from '../stores/nova.js'
  import Icon from '../ui/Icon.svelte'
  
  let { onclose = null } = $props()
  let lookup = $state('')
  let selectedIndex = $state(0)
  let hits = $state([])

  let inputEl = $state(null)
</script>

<div
  role="dialog"
  aria-modal="true"
  class="fixed inset-0 z-50"
  data-testid="nova-palette"
  onkeydown={(e) => e.key === 'Escape' && onclose?.()}
>
  <div class="absolute inset-0 bg-black/50" onclick={() => onclose?.()} />
  <div class="relative mx-auto mt-24 w-11/12 max-w-xl overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-xl dark:border dark:border-gray-700">
    <div class="flex items-center gap-3 border-b px-4 dark:border-gray-700">
      <Icon name="search" class="w-4 h-4 text-gray-400" />
      <input
        bind:this={inputEl}
        bind:value={lookup}
        autofocus
        class="w-full py-3 text-sm bg-transparent outline-none"
        placeholder="Type to search resources…"
        data-testid="nova-palette-input"
        oninput={() => {
          const q = lookup.trim().toLowerCase()
          lookupResults = q
            ? nova.resourceNames()
                .filter((n) => n.toLowerCase().includes(q))
                .slice(0, 8)
            : []
          selectedIndex = 0
        }}
        onkeydown={(e) => {
          if (e.key === 'ArrowDown') { selectedIndex = Math.min(selectedIndex + 1, lookupResults.length - 1); e.preventDefault() }
          else if (e.key === 'ArrowUp') { selectedIndex = Math.max(selectedIndex - 1, 0); e.preventDefault() }
          else if (e.key === 'Enter' && lookupResults[selectedIndex]) { nova.visit(lookupResults[selectedIndex]) }
        }}
      />
    </div>
    {#if lookup && lookupResults.length}
      <ul class="max-h-64 overflow-y-auto py-1" data-testid="nova-palette-results">
        {#each lookupResults as name, i}
          <li
            class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-200"
            class:bg-gray-100={i === selectedIndex}
            onclick={() => nova.visit(name)}
            data-testid="nova-palette-result"
          >
            <Icon name="search" class="w-4 h-4 text-gray-400" />
            <span>{name}</span>
          </li>
        {/each}
      </ul>
    {:else if lookup}
      <p class="px-4 py-3 text-sm text-gray-400" data-testid="nova-palette-empty">No resources match “{lookup}”</p>
    {/if}
  </div>
</div>

<script>
  /* Menu.svelte — vertical menu, one roving tab stop (HeadlessUI). Focus moves
     on ArrowDown/ArrowUp/Home/End over the data-testid nova-menu-item group. */
  let { items = [], value = items[0]?.value ?? null, onSelect = null, className = '' } = $props()
  let root = $state()

  function onKey(i) {
    return (event) => {
      const next = { ArrowDown: i + 1, ArrowUp: i - 1, Home: 0, End: items.length - 1 }[event.key]
      if (next === undefined) return
      event.preventDefault()
      const nodeList = [...root.querySelectorAll('[data-testid="nova-menu-item"]')]
      if (next >= 0 && next < nodeList.length) nodeList[next].focus()
    }
  }
</script>

<ul bind:this={root} role="menu" aria-orientation="vertical" data-testid="nova-menu" class={`py-1 ${className}`}>
  {#each items as item, i (String(i))}
    <li role="presentation">
      <button
        type="button"
        role="menuitem"
        data-testid="nova-menu-item"
        tabindex={item.value === value ? 0 : -1}
        aria-current={item.value === value ? 'true' : undefined}
        onkeydown={onKey(i)}
        onclick={() => onSelect?.(item)}
        class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100"
      >
        {item.label}
      </button>
    </li>
  {/each}
</ul>

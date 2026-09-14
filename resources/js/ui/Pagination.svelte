<script>
  /* Pagination.svelte — Nova pagination, aria-current="page" marker. */
  let { page = 1, last = 1, onNavigate = null } = $props()
  function range(from, to) {
    return Array.from({ length: to - from + 1 }, (_, o) => from + o)
  }
</script>

<nav role="navigation" aria-label="Pagination" data-testid="nova-pagination">
  <button type="button" onclick={() => onNavigate?.(page - 1)} disabled={page <= 1} aria-label="Previous page">
    « Prev
  </button>

  {#each range(1, last) as p (p)}
    <button
      type="button"
      aria-current={p === page ? 'page' : undefined}
      onclick={() => onNavigate?.(p)}
      class={p === page ? 'is-active' : ''}
    >
      {p}
    </button>
  {/each}

  <button type="button" onclick={() => onNavigate?.(page + 1)} disabled={page >= last} aria-label="Next page">
    Next »
  </button>
</nav>

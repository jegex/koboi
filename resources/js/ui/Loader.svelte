<script>
  /* Loader.svelte — Nova-style bouncing-dot loader. No spinner dependency. */
  let { size = 'md', className = '' } = $props()
  const dots = { sm: 'h-2 w-2', md: 'h-2.5 w-2.5', lg: 'h-3 w-3' }
</script>

<span class={`inline-flex items-center gap-1 ${className}`} role="status" aria-label="Loading" data-testid="nova-loader">
  <span class={'animate-bounce rounded-full bg-current ' + (dots[size] ?? dots.md)} />
  <span class={'animate-bounce rounded-full bg-current [animation-delay:0.15s] ' + (dots[size] ?? dots.md)} />
  <span class={'animate-bounce rounded-full bg-current [animation-delay:0.3s] ' + (dots[size] ?? dots.md)} />
</span>

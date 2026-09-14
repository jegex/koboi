<script>
  /* Icon.svelte — inline stroke SVG (24x24, currentColor), NO @heroicons/vue.
     Tiny map of the 6 glyphs the Nova shell actually uses. Unknown names fall
     back to a neutral asterisk path so dynamic-name usage never crashes. */
  let { name, size = 'w-5 h-5', class: className = '', label = null } = $props()

  const glyphs = {
    search: 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
    'x-mark': 'M6 18L18 6M6 6l12 12',
    check: 'M4.5 12.75l6 6 9-13.5',
    'chevron-right': 'M9 5l7 7-7 7',
    'chevron-down': 'M19 9l-7 7-7-7',
    bell: 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
  }
  const fallback = 'M12 9v3.75M12 18.375a.375.375 0 11-.007 0 .375.375 0 010 0'
</script>

<svg
  xmlns="http://www.w3.org/2000/svg"
  fill="none"
  viewBox="0 0 24 24"
  stroke-width="1.5"
  stroke="currentColor"
  stroke-linecap="round"
  stroke-linejoin="round"
  aria-hidden={label === null ? 'true' : undefined}
  aria-label={label ?? undefined}
  class={`inline ${size} ${className}`}
  data-testid="nova-icon"
>
  <path d={glyphs[name] ?? fallback} />
</svg>

<script>
  /* Checkbox.svelte — native checkbox with inline check overlay. */
  import Icon from './Icon.svelte'
  let { checked = false, disabled = false, label = null, className = '' } = $props()
</script>

<label class={`inline-flex items-center gap-2 ${className}`} data-testid="nova-checkbox">
  <span class="relative inline-flex h-5 w-5 items-center justify-center">
    <input type="checkbox" {checked} {disabled} class="h-5 w-5" data-testid="nova-checkbox-input" />
    {#if checked}
      <Icon name="check" size="w-4 h-4" className="pointer-events-none absolute text-white" />
    {/if}
  </span>
  {#if label !== null}
    <span data-testid="nova-checkbox-label">{label}</span>
  {/if}
  <slot />
</label>

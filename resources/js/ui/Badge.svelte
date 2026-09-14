<script>
  let { danger = false, success = false, warning = false, label = null, className = '' } = $props()
  const tones = {
    danger: 'bg-red-50 text-red-700 ring-red-600/20',
    success: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    warning: 'bg-amber-50 text-amber-700 ring-amber-600/20',
    default: 'bg-gray-50 text-gray-700 ring-gray-600/20',
  }
  const tone = danger ? 'danger' : success ? 'success' : warning ? 'warning' : 'default'
</script>

<span
  class={`inline-flex items-center rounded-md px-1.5 py-0.5 text-xs font-medium ring-1 ring-inset ${tones[tone]} ${className}`}
  data-testid="nova-badge"
>
  {#if label !== null}
    <span data-testid="nova-badge-label">{label}</span>
  {/if}
  <slot />
</span>

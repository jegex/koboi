<script>
  /* Metrics.svelte — metric cards grid (PartitionMetrics/ValueMetric/TrendMetric
     family, generic). Each item: { key, label, value, suffix?, trend? }. */
  let { items = [], className = '' } = $props()
</script>

<div
  class={`grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 ${className}`}
  data-testid="nova-metrics"
>
  {#each items as item (item.key)}
    <div class="rounded-lg bg-white p-4 shadow-sm ring-1 ring-gray-200" data-testid="nova-metric">
      <p class="text-sm text-gray-500">{item.label}</p>
      <p class="mt-1 text-2xl font-semibold text-gray-900">{item.value}{#if item.suffix !== undefined}<span class="text-sm text-gray-400">{item.suffix}</span>{/if}</p>
      {#if item.trend !== undefined}
        <p class="mt-1 text-xs text-gray-500" data-testid="nova-metric-trend">{item.trend}</p>
      {/if}
    </div>
  {/each}
</div>

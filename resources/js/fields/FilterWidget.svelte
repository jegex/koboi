<script>
  /* FilterWidget.svelte — filter UI (filter-type picker + apply/reset) bound
     to the fields filters API. Each active filter becomes a row with its own
     type picker; Apply resolves values, Reset clears. */
  import { applyFilters, resetFilters } from './filters/index.js'
  import { FilterDefinition, filtersFor } from './filters/definitions.js'
  let { filters = [], onChange = null } = $props()
  let pending = $state()
  function resolveFilter(f) {
    const def = filtersFor(f.type)?.definition
    return def?.resolve?.(f.value) ?? f
  }
  function apply() {
    onChange?.(filters.map(resolveFilter))
  }
  function reset() {
    onChange?.([])
  }
</script>

<div class="w-full" data-testid="nova-filter-widget">
  <div class="mb-1 flex items-center justify-between">
    <h3 class="text-sm font-medium text-gray-900">Filters</h3>
    <div class="flex items-center gap-1">
      <button type="button" class="rounded px-2 py-1 text-xs text-gray-500 hover:text-gray-700" onclick={reset}>Reset</button>
      <button type="button" class="rounded bg-primary px-2 py-1 text-xs text-white" onclick={apply}>Apply</button>
    </div>
  </div>
  <div class="flex flex-col gap-1" data-testid="nova-filter-list">
    {#each filters as filter, i (filter.key ?? String(i))}
      <label class="flex gap-1 text-xs">
        <select
          value={filter.operator ?? filter.type}
          data-testid="nova-filter-operator"
          class="rounded border border-gray-300 text-xs"
        >
          {#each filter.operators ?? operatorsFor(filter.type) as op}
            <option value={op.value}>{op.label}</option>
          {/each}
        </select>
        <input
          type="text"
          value={filter.value ?? ''}
          data-testid="nova-filter-value"
          class="w-full rounded border border-gray-300 px-1.5 py-1 text-xs"
          placeholder={filter.placeholder ?? 'Filter value…'}
        />
      </label>
    {/each}
  </div>
  <FilterDefinition />
  <slot />
</div>

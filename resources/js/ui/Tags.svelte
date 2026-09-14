<script>
  /* Tags.svelte — Nova tags list: removable chips (x-mark via Icon), plus slot
     for the "Add tag" control Nova renders alongside. */
  import Icon from './Icon.svelte'
  let { tags = [], onRemove = null, className = '' } = $props()
</script>

<div
  class={`flex flex-wrap items-center gap-1.5 ${className}`}
  data-testid="nova-tags"
>
  {#each tags as tag, i (tag)}
    <span class="inline-flex items-center gap-0.5 rounded-md bg-gray-100 px-1.5 py-0.5 text-xs text-gray-700" data-testid="nova-tag">
      {tag}
      <button
        type="button"
        class="-ml-0.5 text-gray-400 hover:text-gray-600"
        aria-label={`Remove ${tag}`}
        data-testid="nova-tag-remove"
        onclick={() => onRemove?.(tag, i)}
      >
        <Icon name="x-mark" size="w-3 h-3" />
      </button>
    </span>
  {/each}
  <slot />
</div>

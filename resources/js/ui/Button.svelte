<script>
  /* Button.svelte — Nova-style button (native <button> / <a href>). */
  import { cn } from '../ui.js'
  let {
    type = 'button',
    variant = 'default',
    size = 'md',
    disabled = false,
    href = null,
    label = null,
    className = '',
  } = $props()

  const variants = {
    default:
      'border border-gray-300 bg-white text-gray-700 shadow-sm hover:bg-gray-50',
    primary: 'bg-primary-500 text-white shadow-sm hover:bg-primary-600',
    danger: 'bg-red-600 text-white shadow-sm hover:bg-red-500',
  }
  const sizes = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-2.5 py-1.5 text-sm',
    lg: 'px-3 py-2 text-base',
  }
  const base =
    'inline-flex items-center justify-center rounded-md font-medium focus-visible:outline-2 focus-visible:outline-offset-2 disabled:opacity-50'
  const classes = cn(base, sizes[size], variants[variant] ?? variants.default, className)
</script>

{#if href !== null}
  <a {href} class={classes} data-testid="nova-button">
    <slot />
    {#if label !== null}<span data-testid="nova-button-label">{label}</span>{/if}
  </a>
{:else}
  <button {type} {disabled} class={classes} data-testid="nova-button">
    <slot />
    {#if label !== null}<span data-testid="nova-button-label">{label}</span>{/if}
  </button>
{/if}

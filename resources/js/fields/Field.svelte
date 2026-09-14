<script>
  /* Field.svelte — Nova field dispatcher. Takes a field definition (def) and a
     variant; resolves the registered component for the field's type + variant
     (:as-style dispatch) and renders inline validation errors per-field via
     the FormValidation util. Falls back to a plain label+value row when no
     component is registered so the shell can still host custom UI. */
  import { fieldComponent } from './registry.js'
  import { errorsForField } from './validation.js'
  let { type = 'text', variant = 'form', label = null, value = null, errors = null, onchange = null, options = [], placeholder = null, className = '' } = $props()
  const Component = fieldComponent(type)
  const error = errorsForField(errors, label)
</script>

{#if Component}
  <svelte:component
    this={Component}
    {variant}
    {label}
    {value}
    {options}
    {placeholder}
    error={error}
    onchange={onchange}
    className={className}
  />
{:else}
  <div class={`flex items-center justify-between gap-2 ${className}`} data-testid="nova-field-plain" data-variant={variant}>
    {#if label}<span class="text-sm text-gray-500">{label}</span>{/if}
    <span class="text-sm text-gray-900">{value ?? '—'}</span>
  </div>
{/if}

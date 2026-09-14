<script>
  export let { name, label, type = "text", value = "", placeholder = "", disabled = false, required = false, onValueChange = null } = $props()
</script>

<label class="block {className}">
  <span class="mb-1 block text-sm font-medium text-gray700">{label}</span>
  <input
    {name}
    {type}
    {value}
    {placeholder}
    {disabled}
    {required}
    oninput={(event) => onValueChange?.(event.currentTarget.value)}
    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:border-blue-500 focus:outline-none disabled:opacity-50 {className}"
    data-testid="nova-input"
  />
  <slot name="prepend" />
  <slot name="append" />
</label>

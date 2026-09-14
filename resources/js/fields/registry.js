/* registry.js — Nova field registry. Dispatch is `:as`-style: register a
   type→component+variants; `fieldComponent(type)` resolves the component for
   the caller (Field.svelte then renders the matching variant). Unregistered
   types fall back to null so shells can host custom UI. Core simple types
   (Text…Password, Date) are pre-registered as native. */
const types = new Map()
const native = ["text", "textarea", "number", "boolean", "select", "email", "password", "date", "time", "url"]

export function registerField(type, component, variants = []) {
  types.set(type, { component, variants })
}

export const FIELD_VARIANTS = ["index", "form", "detail", "filter"]

function variantsFor(type, variants = []) {
  if (variants.length > 0) return variants
  if (native.includes(type)) return FIELD_VARIANTS
  return []
}

export function fieldComponent(type) {
  return types.get(type)?.component ?? null
}

export function fieldVariants(type) {
  return types.get(type)?.variants ?? variantsFor(type)
}

export function nativeFieldTypes() {
  return native
}

export const fieldRegistry = { registerField, fieldComponent, fieldVariants, nativeFieldTypes }

/* fields/index.js — Nova field engine barrel. Re-exports the registry plus a
   tiny createFields() helper that pre-registers the core fields. */
export { registerField, fieldComponent, fieldVariants, nativeFieldTypes } from './registry.js'
export { validateString, validateNumber, validateRequired, errorsForField } from './validation.js'
export { default as Field } from './Field.svelte'
export { default as Filter } from './filters/Filter.svelte'

import TextField from './TextField.svelte'
import TextareaField from './TextareaField.svelte'
import NumberField from './NumberField.svelte'
import BooleanField from './BooleanField.svelte'
import SelectField from './SelectField.svelte'
import DateField from './DateField.svelte'
import EmailField from './EmailField.svelte'
import PasswordField from './PasswordField.svelte'
import { registerField, fieldVariants } from './registry.js'
export { default as TextField } from './TextField.svelte'
export { default as TextareaField } from './TextareaField.svelte'
export { default as NumberField } from './NumberField.svelte'
export { default as BooleanField } from './BooleanField.svelte'
export { default as SelectField } from './SelectField.svelte'
export { default as DateField } from './DateField.svelte'
export { default as EmailField } from './EmailField.svelte'
export { default as PasswordField } from './PasswordField.svelte'

export function createFields(params = {}) {
  const registry = []
  for (const [type, component] of [
    ['text', TextField],
    ['textarea', TextareaField],
    ['number', NumberField],
    ['boolean', BooleanField],
    ['select', SelectField],
    ['date', DateField],
    ['email', EmailField],
    ['password', PasswordField],
  ]) {
    registerField(type, component, fieldVariants(type))
    registry.push([type, component])
  }
  return registry
}

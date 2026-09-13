import { getContext, hasContext, setContext } from 'svelte'
import filled from '../util/filled.js'
import isEqualsToValue from '../util/fieldValue.js'

const FIELD_CONTEXT = Symbol.for('novia.field')

/**
 * Provide the current field to the component tree so consumers can resolve
 * field value primitives without a `mixins/FieldValue` dependency.
 *
 * @param {any} field
 */
export function provideField(field) {
  setContext(FIELD_CONTEXT, field)
}

/**
 * Retrieve the current field provided by an ancestor. Returns null when the
 * component is not rendered within a field.
 *
 * @returns {any}
 */
export function useField() {
  return hasField() ? getContext(FIELD_CONTEXT) : null
}

/**
 * @returns {boolean}
 */
export function hasField() {
  return hasContext(FIELD_CONTEXT)
}

/**
 * Determine if the given value is equal to the current field value.
 *
 * @param {any} value
 * @param {any} [field]
 * @returns {boolean}
 */
export function useIsEqualsToValue(value, field = useField()) {
  if (field === null) {
    return false
  }

  return isEqualsToValue(field.value, value)
}

/**
 * @param {any} [field]
 * @returns {boolean}
 */
export function useFieldHasValue(field = useField()) {
  return filled(field?.value)
}

/**
 * @param {any} [field]
 * @returns {string|null}
 */
export function useFieldValue(field = useField()) {
  if (!filled(field?.value)) {
    return null
  }

  return String(field.value)
}

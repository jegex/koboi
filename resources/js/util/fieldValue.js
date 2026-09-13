import filled from './filled'
import isArray from 'lodash/isArray'

/**
 * Determine if the given value is equals to the field's value.
 *
 * @param {any} fieldValue The field's raw value.
 * @param {any} value
 * @returns {boolean}
 */
export default function isEqualsToValue(fieldValue, value) {
  if (isArray(fieldValue) && filled(value)) {
    return Boolean(
      fieldValue.includes(value) || fieldValue.includes(value.toString())
    )
  }

  return Boolean(
    fieldValue === value ||
      fieldValue?.toString() === value ||
      fieldValue === value?.toString() ||
      fieldValue?.toString() === value?.toString()
  )
}

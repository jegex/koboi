import { describe, expect, test } from 'vitest'
import isEqualsToValue from '../../util/fieldValue.js'

const filled = value => value !== undefined && value !== null && value !== ''

function makeField(value) {
  return { value }
}

describe('util/fieldValue (ported from FieldValue mixin)', () => {
  test('it can validate given value as integer', () => {
    let field = makeField(5)

    expect(isEqualsToValue(field.value, 5)).toBe(true)
    expect(isEqualsToValue(field.value, '5')).toBe(true)
    expect(isEqualsToValue(field.value, 0)).toBe(false)
    expect(isEqualsToValue(field.value, '0')).toBe(false)
    expect(isEqualsToValue(field.value, null)).toBe(false)
    expect(isEqualsToValue(field.value, '')).toBe(false)
    expect(isEqualsToValue(field.value, 'laravel')).toBe(false)
    expect(isEqualsToValue(field.value, 'nova')).toBe(false)
  })

  test('it can validate given value as string', () => {
    let field = makeField('laravel')

    expect(isEqualsToValue(field.value, 5)).toBe(false)
    expect(isEqualsToValue(field.value, '5')).toBe(false)
    expect(isEqualsToValue(field.value, 0)).toBe(false)
    expect(isEqualsToValue(field.value, '0')).toBe(false)
    expect(isEqualsToValue(field.value, null)).toBe(false)
    expect(isEqualsToValue(field.value, '')).toBe(false)
    expect(isEqualsToValue(field.value, 'laravel')).toBe(true)
    expect(isEqualsToValue(field.value, 'nova')).toBe(false)
  })

  test('it can validate given value as empty string', () => {
    let field = makeField('')

    expect(isEqualsToValue(field.value, 5)).toBe(false)
    expect(isEqualsToValue(field.value, '5')).toBe(false)
    expect(isEqualsToValue(field.value, 0)).toBe(false)
    expect(isEqualsToValue(field.value, '0')).toBe(false)
    expect(isEqualsToValue(field.value, null)).toBe(false)
    expect(isEqualsToValue(field.value, '')).toBe(true)
    expect(isEqualsToValue(field.value, 'laravel')).toBe(false)
    expect(isEqualsToValue(field.value, 'nova')).toBe(false)
  })

  test('it can validate given value as null', () => {
    let field = makeField(null)

    expect(isEqualsToValue(field.value, 5)).toBe(false)
    expect(isEqualsToValue(field.value, '5')).toBe(false)
    expect(isEqualsToValue(field.value, 0)).toBe(false)
    expect(isEqualsToValue(field.value, '0')).toBe(false)
    expect(isEqualsToValue(field.value, null)).toBe(true)
    expect(isEqualsToValue(field.value, '')).toBe(false)
    expect(isEqualsToValue(field.value, 'laravel')).toBe(false)
    expect(isEqualsToValue(field.value, 'nova')).toBe(false)
  })
})

import { describe, it, expect } from 'vitest'
import { registerField, fieldComponent, fieldVariants, nativeFieldTypes } from '../fields/registry.js'
import { errorsForField } from '../fields/validation.js'

describe('Nova field engine (#9)', () => {
  it('registry dispatches a registered type to its component', () => {
    const Stub = () => null
    registerField('nova-text', Stub, ['index', 'form', 'detail', 'filter'])
    expect(fieldComponent('nova-text')).toBe(Stub)
  })

  it('registry reports the variants a field type supports', () => {
    registerField('nova-select', () => null, ['index', 'form'])
    expect(fieldVariants('nova-select')).toEqual(['index', 'form'])
  })

  it('native field types are always available', () => {
    const natives = nativeFieldTypes()
    for (const t of ['text', 'number', 'boolean', 'select', 'date']) {
      expect(natives).toContain(t)
    }
  })

  it('errorsForField returns the message for a present attribute and undefined otherwise', () => {
    expect(errorsForField({ title: ['The title is required.'] }, 'title')).toEqual(['The title is required.'])
    expect(errorsForField({ title: ['The title is required.'] }, 'missing')).toBeUndefined()
  })

  it('field component contracts align with the Nova field-definition variants', () => {
    for (const type of nativeFieldTypes()) {
      const variants = fieldVariants(type)
      expect(variants.length).toBeGreaterThan(0)
      for (const v of variants) expect(['index', 'form', 'detail', 'filter']).toContain(v)
    }
  })
})

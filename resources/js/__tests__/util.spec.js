import { describe, expect, it } from 'vitest'

import filled from '../util/filled.js'
import hourCycle from '../util/hourCycle.js'
import increaseOrDecrease from '../util/increaseOrDecrease.js'
import minimum from '../util/minimum.js'
import singularOrPlural from '../util/singularOrPlural.js'
import slugify from '../util/slugify.js'
import url from '../util/url.js'
import { escapeUnicode } from '../util/escapeUnicode.js'
import __ from '../util/localization.js'
import { parseColor } from '../util/color.js'

describe('util/filled', () => {
  it('returns false for empty values', () => {
    expect(filled(null)).toBe(false)
    expect(filled(undefined)).toBe(false)
    expect(filled('')).toBe(false)
  })

  it('returns true for filled values', () => {
    expect(filled('a')).toBe(true)
    expect(filled(0)).toBe(true)
    expect(filled(false)).toBe(true)
  })
})

describe('util/hourCycle', () => {
  it('returns 12 or 24 based on locale', () => {
    expect([12, 24]).toContain(hourCycle('en-US'))
    expect([12, 24]).toContain(hourCycle('id-ID'))
  })
})

describe('util/increaseOrDecrease', () => {
  it('returns null when starting value is zero', () => {
    expect(increaseOrDecrease(5, 0)).toBeNull()
  })

  it('computes percentage change', () => {
    expect(increaseOrDecrease(150, 100)).toBe(50)
    expect(increaseOrDecrease(50, 100)).toBe(-50)
    expect(increaseOrDecrease(100, 50)).toBe(100)
  })
})

describe('util/minimum', () => {
  it('resolves with the original promise result', async () => {
    const result = await minimum(Promise.resolve('ok'), 0)
    expect(result).toBe('ok')
  })
})

describe('util/singularOrPlural', () => {
  it('pluralizes and singularizes based on value', () => {
    expect(singularOrPlural(1, 'user')).toBe('user')
    expect(singularOrPlural(2, 'user')).toBe('users')
    expect(singularOrPlural(0, 'user')).toBe('users')
  })
})

describe('util/slugify', () => {
  it('slugifies a string', () => {
    expect(slugify('Hello World')).toBe('hello-world')
    expect(slugify('Foo Bar', '_')).toBe('foo_bar')
  })
})

describe('util/url', () => {
  it('builds a url with base, path, and params', () => {
    expect(url('/nova', '/users', { search: 'foo' })).toBe(
      '/nova/users?search=foo'
    )
    expect(url('/nova', '/users')).toBe('/nova/users')
    expect(url('/nova', '/users', { empty: null })).toBe('/nova/users')
  })
})

describe('util/escapeUnicode', () => {
  it('escapes non-ascii characters', () => {
    expect(escapeUnicode('hello')).toBe('hello')
    expect(escapeUnicode('héllo')).toContain('\\u00e9')
  })
})

describe('util/localization', () => {
  it('returns key when no translation exists', () => {
    globalThis.Nova = { config: () => ({}) }
    expect(__('missing.key')).toBe('missing.key')
  })

  it('translates and replaces placeholders', () => {
    globalThis.Nova = {
      config: () => ({
        'Hello :name': 'Halo :name',
      }),
    }

    expect(__('Hello :name', { name: 'Arka' })).toBe('Halo Arka')
  })
})

describe('util/parseColor', () => {
  it('parses hex colors', () => {
    const parsed = parseColor('#ff0000')
    expect(parsed.color).toEqual([255, 0, 0])
    expect(parsed.alpha).toBe(1)
  })

  it('parses rgba with alpha', () => {
    const parsed = parseColor('rgba(255, 0, 0, 0.5)')
    expect(parsed.color).toEqual([255, 0, 0])
    expect(parsed.alpha).toBe(0.5)
  })

  it('parses hsl', () => {
    const parsed = parseColor('hsl(120, 100%, 50%)')
    expect(parsed.color).toEqual([0, 255, 0])
  })

  it('parses space-syntax rgb with alpha', () => {
    const parsed = parseColor('rgb(255 0 0 / 50%)')
    expect(parsed.alpha).toBe(0.5)
  })

  it('returns undefined for unknown values', () => {
    expect(parseColor('red')).toBeUndefined()
    expect(parseColor('bogus')).toBeUndefined()
  })
})
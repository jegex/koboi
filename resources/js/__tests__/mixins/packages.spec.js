import { describe, expect, test, afterAll } from 'vitest'

import __ from '../../util/localization.js'
import filled from '../../util/filled.js'

afterAll(() => {
  delete global.Nova
})

describe('util/localization (ported from mixins/packages)', () => {
  test('it can use localization', () => {
    global.Nova = {
      config(key) {
        return this.appConfig[key] ?? null
      },
      appConfig: {
        translations: {
          taylorotwell: 'Taylor Otwell',
          'Laravel Nova :version': 'Laravel Nova v:version',
        },
      },
    }

    expect(__('taylorotwell')).toBe('Taylor Otwell')
    expect(__('Laravel Nova')).toBe('Laravel Nova')
    expect(__('Laravel Nova :version', { version: '4.0.0' })).toBe(
      'Laravel Nova v4.0.0'
    )
  })

  test('it can use filled', () => {
    expect(filled('')).toBe(false)
    expect(filled('nova')).toBe(true)
    expect(filled(0)).toBe(true)
    expect(filled([])).toBe(true)
  })
})

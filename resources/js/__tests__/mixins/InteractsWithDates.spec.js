import { afterAll, describe, expect, test } from 'vitest'

import hourCycle from '../../util/hourCycle.js'
import { useNovaConfig } from '../../context/nova.js'

afterAll(() => {
  delete globalThis.Nova
})

describe('context/InteractsWithDates (ported from nova mixin)', () => {
  test('it can get user timezone', () => {
    globalThis.Nova = {
      config(key) {
        return this.appConfig[key] ?? null
      },
      appConfig: {
        timezone: 'UTC',
        userTimezone: 'Asia/Kuala_Lumpur',
      },
    }

    expect(useNovaConfig('userTimezone') ?? useNovaConfig('timezone')).toBe(
      'Asia/Kuala_Lumpur'
    )
  })

  test('it can fallback to application timezone if user does not define timezone', () => {
    globalThis.Nova = {
      config(key) {
        return this.appConfig[key] ?? null
      },
      appConfig: {
        timezone: 'UTC',
      },
    }

    expect(useNovaConfig('userTimezone') ?? useNovaConfig('timezone')).toBe(
      'UTC'
    )
  })

  test('it can determine if user is used to 12 hour time', () => {
    expect(hourCycle('en-US')).toBe(12)
  })
})

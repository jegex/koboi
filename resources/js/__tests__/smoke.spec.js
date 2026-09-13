import { describe, expect, it } from 'vitest'

import '../app.js'
import { cn, createNovaUi } from '../ui.js'
import Nova from '../nova.js'

describe('koboi smoke', () => {
  it('keeps createNovaApp as the boot entry without window.Vue', () => {
    expect(typeof window.createNovaApp).toBe('function')
    expect(window.Vue).toBeUndefined()
  })

  it('creates a Nova instance from the config', () => {
    const nova = window.createNovaApp({ appName: 'Koboi', base: '/nova' })

    expect(nova).toBeInstanceOf(Nova)
    expect(nova.config('appName')).toBe('Koboi')
    expect(nova.url('/users')).toBe('/nova/users')
  })

  it('exposes LaravelNovaUtil on window', () => {
    expect(window.LaravelNovaUtil).toBeDefined()
    expect(typeof window.LaravelNovaUtil.filled).toBe('function')
  })

  it('provides the ui utility surface', () => {
    expect(cn('a', null, 'b')).toBe('a b')

    const ui = createNovaUi({ version: '5.9.5 (Silver Surfer)' })
    expect(ui.config.version).toBe('5.9.5 (Silver Surfer)')
    expect(ui.cn('x', undefined, 'y')).toBe('x y')
  })
})
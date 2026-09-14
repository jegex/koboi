import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { createThemeStore } from '../stores/theme.js'

describe('koboi shell — AppLayout & theme (#10)', () => {
  beforeEach(() => { localStorage.clear(); document.documentElement.classList.remove('dark') })
  afterEach(() => { localStorage.clear(); document.documentElement.classList.remove('dark') })

  it('defaults to light theme wired to the <html> dark class', () => {
    const theme = createThemeStore()
    let value
    theme.subscribe(v => (value = v))
    expect(value).toBe(false)
    expect(document.documentElement.classList.contains('dark')).toBe(false)
  })

  it('toggle flips the html class and persists to localStorage', () => {
    const theme = createThemeStore()
    theme.toggle()
    expect(document.documentElement.classList.contains('dark')).toBe(true)
    expect(localStorage.getItem('nova-theme')).toBe('dark')
    theme.toggle()
    expect(localStorage.getItem('nova-theme')).toBe('light')
  })

  it('respects persisted theme on store creation (survives refresh)', () => {
    localStorage.setItem('nova-theme', 'dark')
    createThemeStore()
    expect(document.documentElement.classList.contains('dark')).toBe(true)
    localStorage.setItem('nova-theme', 'light') // flip persisted value, refresh-like re-create
    createThemeStore()
    expect(document.documentElement.classList.contains('dark')).toBe(false)
  })
})

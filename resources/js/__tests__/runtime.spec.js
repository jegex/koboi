import { render, screen } from '@testing-library/svelte'
import { describe, expect, it, vi } from 'vitest'

import { page } from '@inertiajs/svelte'
import '../app.js'
import AppLayout from '../layouts/AppLayout.svelte'
import Dashboard from './fixtures/Dashboard.svelte'
import Nova from '../nova.js'
import { createNovaStore } from '../stores/nova.js'

const makeConfig = overrides => ({
  appName: 'Novia',
  base: '/nova',
  initialPath: '/dashboard',
  resources: [{ uriKey: 'users', label: 'Users' }],
  brandColors: {},
  fortifyFeatures: [],
  translations: {},
  debug: false,
  withAuthentication: true,
  ...overrides,
})

describe('AppLayout', () => {
  it('renders the active page via svelte:component', () => {
    render(AppLayout, {
      props: {
        component: Dashboard,
        props: { greeting: 'Hai' },
      },
    })

    expect(screen.getByTestId('nova-app')).toBeInTheDocument()
    expect(screen.getByTestId('dashboard-page')).toHaveTextContent('Hai')
  })
})

describe('nova runtime', () => {
  it('exposes event bus callback surface', () => {
    const nova = new Nova(makeConfig())
    const listener = vi.fn()

    nova.$on('test-event', listener)
    nova.$emit('test-event', 'payload')

    expect(listener).toHaveBeenCalledWith('payload')

    nova.$off('test-event', listener)
    nova.$emit('test-event', 'payload2')

    expect(listener).toHaveBeenCalledTimes(1)
  })

  it('exposes config/url helpers', () => {
    const nova = new Nova(makeConfig())

    expect(nova.config('appName')).toBe('Novia')
    expect(nova.url('/users')).toBe('/nova/users')
    expect(nova.url('/')).toBe('/nova/dashboard')
    expect(nova.hasSecurityFeatures()).toBe(false)
    expect(nova.missingResource('posts')).toBe(true)
    expect(nova.missingResource('users')).toBe(false)
  })

  it('exposes a configured request instance', () => {
    const nova = new Nova(makeConfig())

    expect(typeof nova.request().get).toBe('function')
    expect(typeof nova.request().post).toBe('function')
  })

  it('exposes form() with a working Form', async () => {
    const nova = new Nova(makeConfig())
    const form = nova.form({ name: 'Arka' })

    expect(form.data().name).toBe('Arka')
    expect(form.errors).toBeInstanceOf(Object)
    expect(typeof form.post).toBe('function')
    expect(typeof form.submit).toBe('function')
  })

  it('registers inertia pages and components', () => {
    const nova = new Nova(makeConfig())

    nova.inertia('Nova.Dashboard', Dashboard)
    expect(nova.pages['Nova.Dashboard']).toBe(Dashboard)

    nova.component('SomeWidget', 'widget')
    expect(nova.hasComponent('someWidget')).toBe(true)
  })

  it('exposes shortcut management', () => {
    const nova = new Nova(makeConfig())

    expect(() => nova.addShortcut('?', vi.fn())).not.toThrow()
    expect(() => nova.disableShortcut('?')).not.toThrow()

    nova.pauseShortcuts()
    nova.resumeShortcuts()
  })

  it('keeps createNovaApp as the boot entry and drops window.Vue', () => {
    expect(window.createNovaApp).toBeTypeOf('function')
    expect(window.Vue).toBeUndefined()
  })
})

describe('nova store sync', () => {
  it('syncs props from inertia into the store', async () => {
    const store = createNovaStore()

    page.props = {
      novaConfig: makeConfig(),
      currentUser: { name: 'Arka' },
      breadcrumbs: [{ label: 'Dashboard' }],
      validLicense: true,
    }

    let user = null
    const unsub = store.currentUser.subscribe(value => {
      user = value
    })

    await store.assignPropsFromInertia()

    expect(user).toEqual({ name: 'Arka' })

    unsub()
  })
})
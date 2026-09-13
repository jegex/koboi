import '@testing-library/jest-dom/vitest'
import { cleanup } from '@testing-library/svelte'
import { afterEach, beforeEach } from 'vitest'

beforeEach(() => {
  const meta = document.createElement('meta')
  meta.name = 'csrf-token'
  meta.content = 'test-csrf-token'
  document.head.appendChild(meta)

  const locale = document.createElement('meta')
  locale.name = 'locale'
  locale.content = 'en'
  document.head.appendChild(locale)

  window.Nova = window.Nova || {
    appConfig: {},
    $emit: () => {},
    $on: () => {},
    redirectToLogin: () => {},
    visit: () => {},
  }
})

afterEach(() => cleanup())
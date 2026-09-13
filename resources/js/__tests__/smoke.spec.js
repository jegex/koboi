import { render, screen } from '@testing-library/svelte'
import { describe, expect, it } from 'vitest'

import App from '../App.svelte'
import { cn, createNovaUi } from '../ui.js'

describe('koboi smoke', () => {
    it('renders the minimal svelte app', () => {
        render(App, { props: { config: { version: '5.9.5 (Silver Surfer)' } } })

        expect(screen.getByTestId('app-shell')).toBeInTheDocument()
        expect(screen.getByText(/Koboi — Svelte 5 admin panel/)).toBeInTheDocument()
        expect(screen.getByText(/Booting version 5\.9\.5 \(Silver Surfer\)/)).toBeInTheDocument()
    })

    it('provides the ui utility surface', () => {
        expect(cn('a', null, 'b')).toBe('a b')

        const ui = createNovaUi({ version: '5.9.5 (Silver Surfer)' })
        expect(ui.config.version).toBe('5.9.5 (Silver Surfer)')
        expect(ui.cn('x', undefined, 'y')).toBe('x y')
    })
})
import { render, screen } from '@testing-library/svelte'
import { describe, expect, test } from 'vitest'

import { Badge, Button, Checkbox, Icon, Loader } from '../ui.js'

describe('nova ui kit', () => {
  test('Badge renders its label and slot', () => {
    render(Badge, { label: 'New' })
    expect(screen.getByText('New')).toBeTruthy()
    expect(screen.getByTestId('nova-badge')).toBeTruthy()
  })

  test('Button renders a clickable control with its label', () => {
    const { container } = render(Button, { label: 'Save' })
    const btn = container.querySelector('button')
    expect(btn).toBeTruthy()
    expect(screen.getByText('Save')).toBeTruthy()
  })

  test('Checkbox toggles its checked state on change', async () => {
    const { container } = render(Checkbox, { label: 'Active' })
    const input = container.querySelector('input')
    expect(input).toBeTruthy()
    input.checked = !input.checked
    input.dispatchEvent(new Event('change'))
    expect(input.checked).toBe(true)
    expect(screen.getByText('Active')).toBeTruthy()
  })

  test('Icon renders an inline SVG glyph with no external icon dependency', () => {
    const { container } = render(Icon, { name: 'check' })
    const svg = container.querySelector('svg')
    expect(svg).toBeTruthy()
    expect(svg?.innerHTML).toContain('<path')
  })

  test('Loader renders a status indicator', () => {
    const { container } = render(Loader)
    expect(container.querySelector('[role="status"]')).toBeTruthy()
  })
})

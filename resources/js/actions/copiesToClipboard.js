import filled from '../util/filled.js'

/**
 * Svelte action that copies the given value to the clipboard when the node is
 * clicked (imperative `CopiesToClipboard` mixin rebuilt as an action).
 *
 * @param {HTMLElement} node
 * @param {{value: string, enabled?: boolean}} [params]
 * @returns {import('svelte/action').ActionReturn<{value: string|(() => string), enabled?: boolean}>}
 */
export default function copiesToClipboard(node, params) {
  const clickable = () => params?.enabled !== false

  const onValue = () => {
    if (!clickable()) {
      return
    }

    const value =
      typeof params?.value === 'function' ? params.value() : params?.value ?? ''

    setClipboard(value)
  }

  node.addEventListener('click', onValue)

  return {
    update(newParams) {
      params = newParams
    },
    destroy() {
      node.removeEventListener('click', onValue)
    },
  }
}

/**
 * Set the clipboard value using a progressive enhancement fallback.
 *
 * @param {string} value
 */
function setClipboard(value) {
  if (navigator.clipboard?.writeText) {
    navigator.clipboard.writeText(value)
    return
  }

  if (window.clipboardData) {
    window.clipboardData.setData('Text', value)
    return
  }

  legacyTemporaryInput(value)
}

/**
 * @param {string} value
 */
function legacyTemporaryInput(value) {
  const input = document.createElement('input')
  const [scrollTop, scrollLeft] = [
    document.documentElement.scrollTop,
    document.documentElement.scrollLeft,
  ]

  document.body.appendChild(input)
  input.value = value
  input.focus()
  input.select()
  document.documentElement.scrollTop = scrollTop
  document.documentElement.scrollLeft = scrollLeft
  document.execCommand('copy')
  input.remove()
}

/**
 * Determine whether the given value can be copied to the clipboard.
 *
 * @param {any} value
 * @returns {boolean}
 */
export function canCopyToClipboard(value) {
  return filled(value)
}

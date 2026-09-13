/**
 * Svelte action that invokes the given callback whenever the Escape key is
 * released while the bound node (or its descendants) holds focus. This is the
 * Svelte-native rebuild of Nova's close-on-escape / modal-abandon behaviours.
 *
 * @param {HTMLElement} node
 * @param {(event: KeyboardEvent) => void} [callback]
 * @returns {import('svelte/action').ActionReturn<(event: KeyboardEvent) => void>}
 */
export default function closesOnEscape(node, callback) {
  const handleEscape = event => {
    if (event.key !== 'Escape') {
      return
    }

    callback?.(event)
  }

  node.addEventListener('keyup', handleEscape)

  return {
    update(next) {
      callback = next
    },
    destroy() {
      node.removeEventListener('keyup', handleEscape)
    },
  }
}

/**
 * Svelte action that traps keyboard focus within the given node, cycling Tab
 * focus back to the first focusable element. Rebuild of Nova's focus-trap /
 * modal-panel-behaviour.
 *
 * @param {HTMLElement} node
 * @returns {import('svelte/action').ActionReturn}
 */
export function trapsFocus(node) {
  const handleKeydown = event => {
    if (event.key !== 'Tab') {
      return
    }

    const focusables = Array.from(
      node.querySelectorAll('a[href], button:not([disabled]), input, textarea, select, [tabindex]:not([tabindex="-1"])')
    ).filter(el => !el.hasAttribute('disabled'))

    if (focusables.length === 0) {
      return
    }

    const first = focusables[0]
    const last = focusables[focusables.length - 1]

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault()
      last.focus()
      return
    }

    if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault()
      first.focus()
    }
  }

  node.addEventListener('keydown', handleKeydown)

  return {
    destroy() {
      node.removeEventListener('keydown', handleKeydown)
    },
  }
}

/**
 * Svelte action that starts polling for new resources while the page is in
 * focusional state. The `supportsPolling` mixin rebuilt as an action that
 * returns the interval handle so consumers can stop it.
 *
 * @param {HTMLElement} node
 * @param {() => void} refresh
 * @param {number} [frequency=5000]
 * @returns {import('svelte/action').ActionReturn<{refresh: () => void, frequency?: number}, {stop: () => void}>}
 */
export function supportsPolling(node, refresh, frequency = 5000) {
  let intervalId = null

  const start = fn => {
    if (intervalId !== null) {
      return
    }

    intervalId = setInterval(fn, frequency)
  }

  const stop = () => {
    if (intervalId !== null) {
      clearInterval(intervalId)
      intervalId = null
    }
  }

  start(refresh)

  return {
    update({ refresh: next, frequency: nextFrequency }) {
      refresh = next
      frequency = nextFrequency ?? frequency
    },
    stop,
    destroy() {
      stop()
    },
  }
}

import { getContext, setContext } from 'svelte'
import filled from '../util/filled.js'

const NOVA_CONTEXT = Symbol.for('novia.nova')

/**
 * Flag set when a provider has actually mounted. We must never call the Svelte
 * `hasContext()` accessor outside of component initialisation (Svelte throws
 * `lifecycle_outside_component`), so consumers that resolve the Nova global in
 * non-component contexts (plain Vitest specs, bare modules) fall back to the
 * global `Nova` instance instead.
 *
 * @type {boolean}
 */
let hasProvided = false

/**
 * Provide the Nova runtime to the current tree.
 *
 * @param {any} nova
 */
export function provideNova(nova) {
  setContext(NOVA_CONTEXT, nova)

  hasProvided = true

  globalThis.Nova = globalThis.Nova ?? nova
}

/**
 * Resolve the Nova runtime. When the component is not mounted within a
 * provider, falls back to the global `Nova` instance (null when absent).
 *
 * @returns {any}
 */
export function useNova() {
  if (hasProvided) {
    return getContext(NOVA_CONTEXT) ?? globalThis.Nova ?? null
  }

  return globalThis.Nova ?? null
}

/**
 * Resolve a configuration value from the Nova runtime.
 *
 * @param {string} key
 * @returns {any}
 */
export function useNovaConfig(key) {
  let nova = useNova()

  if (nova === null) {
    return null
  }

  return nova.config(key) ?? null
}

/**
 * Determine whether the current Nova runtime has the given configuration.
 *
 * @param {string} key
 * @returns {boolean}
 */
export function useNovaHasConfig(key) {
  return filled(useNovaConfig(key))
}

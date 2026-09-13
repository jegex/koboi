import { router } from '@inertiajs/svelte'

/**
 * @typedef {import('../nova.js').default} NovaApp
 * @typedef {import('../stores/index.js')} Store
 */

/**
 * @param {NovaApp} app
 * @param {ReturnType<typeof import('../stores/index.js').createStore>} store
 */
export function setupInertia(app, store) {
  router.on('before', () => {
    app.debug('Syncing Inertia props to the store via `inertia:before`...')
    store.nova.assignPropsFromInertia()
  })

  router.on('navigate', () => {
    app.debug('Syncing Inertia props to the store via `inertia:navigate`...')
    store.nova.assignPropsFromInertia()
  })

  router.on('start', () => app.$progress.start())
  router.on('finish', () => app.$progress.done())
}

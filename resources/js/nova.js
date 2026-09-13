import { Form } from './util/FormValidation.js'
import { setupAxios } from './bootstrap/axios.js'
import { setupInertia } from './bootstrap/inertia.js'
import { setupNumbro } from './bootstrap/numbro.js'
import url from './util/url.js'
import { mount } from 'svelte'
import { createInertiaApp, Link, router } from '@inertiajs/svelte'
import { registerViews } from './components.js'
import { registerFields } from './fields.js'
import Mousetrap from 'mousetrap'
import { createStore, registerResourceStore } from './stores/index.js'
import NProgress from 'nprogress'
import camelCase from 'lodash/camelCase'
import isString from 'lodash/isString'
import omit from 'lodash/omit'
import upperFirst from 'lodash/upperFirst'
import Emitter from 'tiny-emitter'
import { Settings } from 'luxon'
import AppLayout from './layouts/AppLayout.svelte'
import LoadingPage from './pages/Loading.svelte'
import { parseColor } from './util/color.js'
import { toast } from './ui/toast.js'

const emitter = new Emitter()

/**
 * @typedef {import('svelte').SvelteComponent} SvelteComponent
 * @typedef {import('svelte/store').Store} Store
 * @typedef {ReturnType<typeof createStore>} NovaStore
 * @typedef {import('axios').AxiosInstance} AxiosInstance
 * @typedef {import('axios').AxiosRequestConfig} AxiosRequestConfig
 * @typedef {Object<string, any>} AppConfig
 * @typedef {import('./util/FormValidation.js').Form} Form
 * @typedef {(this: Nova, store: NovaStore) => void} BootingCallback
 * @typedef {(this: Nova, store: NovaStore) => void} BootedCallback
 */

export default class Nova {
  /**
   * @param {AppConfig} config
   */
  constructor(config) {
    /**
     * @protected
     * @type {Array<BootingCallback>}
     */
    this.bootingCallbacks = []

    /**
     * @protected
     * @type {Array<BootedCallback>}
     */
    this.bootedCallbacks = []

    /** @readonly */
    this.appConfig = config

    /**
     * @private
     * @type {boolean}
     */
    this.useShortcuts = true

    /**
     * @protected
     * @type {{[key: string]: any}}
     */
    this.pages = {}

    /**
     * @private
     * @type {{[key: string]: any}}
     */
    this.components = {}

    /** @protected */
    this.$toasted = { show: toast }

    /** @public */
    this.$progress = NProgress

    /** @public */
    this.$router = router

    if (config.debug === true) {
      /** @readonly */
      this.$testing = {
        timezone: timezone => {
          Settings.defaultZoneName = timezone
        },
      }
    }

    /** @private */
    this.__started = false

    /** @private */
    this.__booted = false

    /** @private */
    this.__liftOff = false
  }

  /**
   * Register booting callback to be called before Nova starts. This is used to bootstrap
   * addons, tools, custom fields, or anything else Nova needs
   *
   * @param {BootingCallback} callback
   */
  booting(callback) {
    if (this.__booted === true) {
      callback.call(this, this.store)
    } else {
      this.bootingCallbacks.push(callback)
    }
  }

  /**
   * Register booted callback to be called before Nova starts. This is used to bootstrap
   * addons, tools, custom fields, or anything else Nova needs
   *
   * @param {BootedCallback} callback
   */
  booted(callback) {
    if (this.__booted === true) {
      callback.call(this, this.store)
    } else {
      this.bootedCallbacks.push(callback)
    }
  }

  /**
   * Execute all of the booting callbacks.
   */
  boot() {
    if (!this.__started || !this.__liftOff || this.__booted) {
      return
    }

    this.debug('engage thrusters')

    /** @type {NovaStore} */
    this.store = createStore()

    this.bootingCallbacks.forEach(callback => callback.call(this, this.store))
    this.bootingCallbacks = []

    this.registerStoreModules()

    setupInertia(this, this.store)

    this.component('Link', Link)
    this.component('InertiaLink', Link)

    registerViews(this)
    registerFields(this)

    let mousetrapDefaultStopCallback = Mousetrap.prototype.stopCallback

    Mousetrap.prototype.stopCallback = (e, element, combo) => {
      if (!this.useShortcuts) {
        return true
      }

      return mousetrapDefaultStopCallback.call(this, e, element, combo)
    }

    Mousetrap.init()

    this.applyTheme()

    this.log('All systems go...')

    this.__booted = true

    this.bootedCallbacks.forEach(callback => callback.call(this, this.store))
    this.bootedCallbacks = []
  }

  countdown() {
    this.log('Initiating Nova countdown...')

    createInertiaApp({
      progress: false,
      resolve: name => {
        const page =
          this.pages[name] != null ? this.pages[name] : LoadingPage

        if (page.layout != null) {
          return { default: page, layout: page.layout }
        }

        return {
          default: page,
          layout: (h, child) =>
            h(AppLayout, {
              component: child.component,
              props: child.props,
            }),
        }
      },
      setup: ({ el, App, props }) => {
        this.debug('engine start')

        /** @protected */
        this.mountTo = el

        /** @protected */
        this.app = mount(App, { target: el, props })
      },
    }).then(() => {
      this.__started = true

      this.debug('engine ready')

      this.boot()
    })
  }

  /**
   * Start the Nova app by calling each of the tool's callbacks and then creating
   * the underlying Svelte instance.
   */
  liftOff() {
    this.log('We have lift off!')

    let currentTheme = null

    new MutationObserver(() => {
      const element = document.documentElement.classList
      const theme = element.contains('dark') ? 'dark' : 'light'

      if (theme !== currentTheme) {
        this.$emit('nova-theme-switched', {
          theme,
          element,
        })

        currentTheme = theme
      }
    }).observe(document.documentElement, {
      attributes: true,
      attributeOldValue: true,
      attributeFilter: ['class'],
    })

    if (this.config('notificationCenterEnabled')) {
      /** @private */
      this.notificationPollingInterval = setInterval(() => {
        if (document.hasFocus()) {
          this.$emit('refresh-notifications')
        }
      }, this.config('notificationPollingInterval'))
    }

    this.__liftOff = true

    this.boot()
  }

  /**
   * Return configuration value from a key.
   *
   * @param  {string} key
   * @returns {any}
   */
  config(key) {
    return this.appConfig[key]
  }

  /**
   * Return a form object configured with Nova's preconfigured axios instance.
   *
   * @param {{[key: string]: any}} data
   * @returns {Form}
   */
  form(data) {
    return new Form(data, {
      http: this.request(),
    })
  }

  /**
   * Return an axios instance configured to make requests to Nova's API
   * and handle certain response codes.
   *
   * @param {AxiosRequestConfig|null} [options=null]
   * @returns {AxiosInstance}
   */
  request(options = null) {
    /** @type AxiosInstance */
    let axios = setupAxios()

    if (options != null) {
      return axios(options)
    }

    return axios
  }

  /**
   * Get the URL from base Nova prefix.
   *
   * @param {string} path
   * @param {any} parameters
   * @returns {string}
   */
  url(path, parameters) {
    if (path === '/') {
      path = this.config('initialPath')
    }

    return url(this.config('base'), path, parameters)
  }

  /**
   * @returns {boolean}
   */
  hasSecurityFeatures() {
    const features = this.config('fortifyFeatures')

    return (
      features.includes('update-passwords') ||
      features.includes('two-factor-authentication')
    )
  }

  /**
   * Register a listener on Nova's built-in event bus
   *
   * @param {string} name
   * @param {Function} callback
   * @param {any} ctx
   */
  $on(...args) {
    emitter.on(...args)
  }

  /**
   * Register a one-time listener on the event bus
   *
   * @param {string} name
   * @param {Function} callback
   * @param {any} ctx
   */
  $once(...args) {
    emitter.once(...args)
  }

  /**
   * Unregister an listener on the event bus
   *
   * @param {string} name
   * @param {Function} callback
   */
  $off(...args) {
    emitter.off(...args)
  }

  /**
   * Emit an event on the event bus
   *
   * @param {string} name
   */
  $emit(...args) {
    emitter.emit(...args)
  }

  /**
   * Determine if Nova is missing the requested resource with the given uri key
   *
   * @param {string} uriKey
   * @returns {boolean}
   */
  missingResource(uriKey) {
    return this.config('resources').find(r => r.uriKey === uriKey) == null
  }

  /**
   * Register a keyboard shortcut.
   *
   * @param {string} keys
   * @param {Function} callback
   */
  addShortcut(keys, callback) {
    Mousetrap.bind(keys, callback)
  }

  /**
   * Unbind a keyboard shortcut.
   *
   * @param {string} keys
   */
  disableShortcut(keys) {
    Mousetrap.unbind(keys)
  }

  /**
   * Pause all keyboard shortcuts.
   */
  pauseShortcuts() {
    this.useShortcuts = false
  }

  /**
   * Resume all keyboard shortcuts.
   */
  resumeShortcuts() {
    this.useShortcuts = true
  }

  /**
   * Register the built-in Svelte store for each resource
   */
  registerStoreModules() {
    this.config('resources').forEach(resource => {
      registerResourceStore(this.store, resource.uriKey)
    })
  }

  /**
   * Register Inertia page.
   *
   * @param {string} name
   * @param {any} component
   */
  inertia(name, component) {
    this.pages[name] = component
  }

  /**
   * Register a custom component.
   *
   * @param {string} name
   * @param {any} component
   */
  component(name, component) {
    if (this.components[name] == null) {
      this.components[name] = component
    }
  }

  /**
   * Check if custom component exists.
   *
   * @param {string} name
   * @returns {boolean}
   */
  hasComponent(name) {
    return Boolean(this.components[upperFirst(camelCase(name))] != null)
  }

  /**
   * Show an info message to the user.
   *
   * @param {string} message
   */
  info(message) {
    this.$toasted.show(message, { type: 'info' })
  }

  /**
   * Show an error message to the user.
   *
   * @param {string} message
   */
  error(message) {
    this.$toasted.show(message, { type: 'error' })
  }

  /**
   * Show a success message to the user.
   *
   * @param {string} message
   */
  success(message) {
    this.$toasted.show(message, { type: 'success' })
  }

  /**
   * Show a warning message to the user.
   *
   * @param {string} message
   */
  warning(message) {
    this.$toasted.show(message, { type: 'warning' })
  }

  /**
   * Format a number using numbro.js for consistent number formatting.
   *
   * @param {number} number
   * @param {Object|string} format
   * @returns {string}
   */
  formatNumber(number, format) {
    const numbro = setupNumbro(
      document.querySelector('meta[name="locale"]').content
    )
    const num = numbro(number)

    if (format !== undefined) {
      return num.format(format)
    }

    return num.format()
  }

  /**
   * Log a message to the console with the NOVA prefix
   *
   * @param {string} message
   * @param {string} [type=log]
   */
  log(message, type = 'log') {
    console[type](`[NOVA]`, message)
  }

  /**
   * Log a message to the console for debugging purpose
   *
   * @param {any} message
   * @param {string} [type=log]
   */
  debug(message, type = 'log') {
    const debugEnabled = (this.config('debug') ?? false) === true

    if (debugEnabled === true) {
      if (type === 'error') {
        console.error(message)
      } else {
        this.log(message, type)
      }
    }
  }

  /**
   * Redirect to login path.
   */
  redirectToLogin() {
    const url =
      !this.config('withAuthentication') && this.config('customLoginPath')
        ? this.config('customLoginPath')
        : this.url('/login')

    this.visit({
      remote: true,
      url,
    })
  }

  /**
   * Visit page using Inertia visit or window.location for remote.
   *
   * @param {{url: string, remote: boolean} | string} path
   * @param {any} [options={}]
   */
  visit(path, options = {}) {
    const openInNewTab = options?.openInNewTab || null

    if (isString(path)) {
      router.visit(this.url(path), omit(options, ['openInNewTab']))
      return
    }

    if (isString(path.url) && path.hasOwnProperty('remote')) {
      if (path.remote === true) {
        if (openInNewTab === true) {
          window.open(path.url, '_blank')
        } else {
          window.location = path.url
        }

        return
      }

      router.visit(path.url, omit(options, ['openInNewTab']))
    }
  }

  applyTheme() {
    const brandColors = this.config('brandColors')

    if (Object.keys(brandColors).length > 0) {
      const style = document.createElement('style')

      let css = Object.keys(brandColors).reduce((carry, v) => {
        let colorValue = brandColors[v]
        let validColor = parseColor(colorValue)

        if (validColor) {
          const [r, g, b] = validColor.color
          const rgbaString = `${r} ${g} ${b} / ${validColor.alpha}`

          return carry + `\n  --colors-primary-${v}: ${rgbaString};`
        }

        return carry + `\n  --colors-primary-${v}: ${colorValue};`
      }, '')

      style.innerHTML = `:root {${css}\n}`

      document.head.append(style)
    }
  }
}
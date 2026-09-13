import { derived, get, writable } from 'svelte/store'
import { usePage } from '@inertiajs/svelte'
import filled from '../util/filled.js'

/**
 * @typedef {{baseUri: string, currentUser: any, currentUserPasswordConfirmed: boolean|null, mainMenu: any[], userMenu: any[], breadcrumbs: any[], resources: any[], version: string, mainMenuShown: boolean, canLeaveModal: boolean, validLicense: boolean, queryStringParams: {[key: string]: string}, compiledQueryStringParams: string}} NovaState
 */

function initialState() {
  return {
    baseUri: '/nova',
    currentUser: null,
    currentUserPasswordConfirmed: null,
    mainMenu: [],
    userMenu: [],
    breadcrumbs: [],
    resources: [],
    version: '5.x',
    mainMenuShown: false,
    canLeaveModal: true,
    validLicense: true,
    queryStringParams: {},
    compiledQueryStringParams: '',
  }
}

export function createNovaStore() {
  const store = writable(initialState())
  const { subscribe, update, set } = store

  const currentUser = derived(store, s => s.currentUser)
  const currentUserPasswordConfirmed = derived(
    store,
    s => s.currentUserPasswordConfirmed ?? false
  )
  const currentVersion = derived(store, s => s.version)
  const mainMenu = derived(store, s => s.mainMenu)
  const userMenu = derived(store, s => s.userMenu)
  const breadcrumbs = derived(store, s => s.breadcrumbs)
  const mainMenuShown = derived(store, s => s.mainMenuShown)
  const canLeaveModal = derived(store, s => s.canLeaveModal)
  const validLicense = derived(store, s => s.validLicense)
  const queryStringParams = derived(store, s => s.queryStringParams)

  async function login({ email, password, remember }) {
    await Nova.request().post(Nova.url('/login'), {
      email,
      password,
      remember,
    })
  }

  async function logout(customLogoutPath) {
    let response = null

    if (!Nova.config('withAuthentication') && customLogoutPath) {
      response = await Nova.request().post(customLogoutPath)
    } else {
      response = await Nova.request().post(Nova.url('/logout'))
    }

    return response?.data?.redirect || null
  }

  async function startImpersonating({ resource, resourceId }) {
    let response = await Nova.request().post(`/nova-api/impersonate`, {
      resource,
      resourceId,
    })

    let redirect = response?.data?.redirect || null

    if (redirect !== null) {
      location.href = redirect
      return
    }

    Nova.visit('/')
  }

  async function stopImpersonating() {
    let response = await Nova.request().delete(`/nova-api/impersonate`)

    let redirect = response?.data?.redirect || null

    if (redirect !== null) {
      location.href = redirect
      return
    }

    Nova.visit('/')
  }

  async function confirmedPasswordStatus() {
    const {
      data: { confirmed },
    } = await Nova.request().get(
      Nova.url('/user-security/confirmed-password-status')
    )

    if (confirmed) {
      passwordConfirmed()
    } else {
      passwordUnconfirmed()
    }
  }

  async function passwordConfirmed() {
    update(s => ({ ...s, currentUserPasswordConfirmed: true }))

    setTimeout(() => passwordUnconfirmed(), 500000)
  }

  async function passwordUnconfirmed() {
    update(s => ({ ...s, currentUserPasswordConfirmed: false }))
  }

  async function assignPropsFromInertia() {
    const props = usePage().props

    let config = props.novaConfig || Nova.appConfig
    let { resources, base, version, mainMenu, userMenu } = config

    let user = props.currentUser
    let validLicense = props.validLicense
    let breadcrumbs = props.breadcrumbs

    Nova.appConfig = config

    update(s => ({
      ...s,
      breadcrumbs: breadcrumbs || [],
      currentUser: user,
      validLicense: validLicense,
      resources: resources,
      baseUri: base,
      version: version,
      mainMenu: mainMenu,
      userMenu: userMenu,
    }))

    await syncQueryString()
  }

  async function fetchPolicies() {
    await assignPropsFromInertia()
  }

  async function syncQueryString() {
    let searchParams = new URLSearchParams(window.location.search)

    update(s => ({
      ...s,
      queryStringParams: Object.fromEntries(searchParams.entries()),
      compiledQueryStringParams: searchParams.toString(),
    }))
  }

  async function updateQueryString(value) {
    let searchParams = new URLSearchParams(window.location.search)
    let page = await Nova.$router.decryptHistory()
    let nextUrl = null

    let current = get(store)

    Object.entries(value).forEach(([i, v]) => {
      if (!filled(v)) {
        searchParams.delete(i)
      } else {
        searchParams.set(i, v || '')
      }
    })

    if (current.compiledQueryStringParams !== searchParams.toString()) {
      if (page.url !== `${window.location.pathname}?${searchParams}`) {
        nextUrl = `${window.location.pathname}?${searchParams}`
      }

      current.compiledQueryStringParams = searchParams.toString()
    }

    Nova.$emit('query-string-changed', searchParams)

    update(s => ({
      ...s,
      compiledQueryStringParams: current.compiledQueryStringParams,
      queryStringParams: Object.fromEntries(searchParams.entries()),
    }))

    return new Promise(resolve => {
      resolve({ searchParams, nextUrl, page })
    })
  }

  return {
    subscribe,
    set,
    update,
    currentUser,
    currentUserPasswordConfirmed,
    currentVersion,
    mainMenu,
    userMenu,
    breadcrumbs,
    mainMenuShown,
    canLeaveModal,
    validLicense,
    queryStringParams,
    login,
    logout,
    startImpersonating,
    stopImpersonating,
    confirmedPasswordStatus,
    passwordConfirmed,
    passwordUnconfirmed,
    assignPropsFromInertia,
    fetchPolicies,
    syncQueryString,
    updateQueryString,
  }
}

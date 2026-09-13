import { mount } from 'svelte'
import App from './App.svelte'

export function createNovaApp(config) {
    const target = document.body

    const app = mount(App, {
        target,
        props: { config },
    })

    return {
        countdown() {},
        liftOff() {},
        _app: app,
    }
}

window.createNovaApp = createNovaApp

export default createNovaApp
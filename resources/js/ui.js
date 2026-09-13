export function cn(...parts) {
    return parts.filter(Boolean).join(' ')
}

export function createNovaUi(config = {}) {
    return {
        config,
        cn,
    }
}

export default {
    cn,
    createNovaUi,
}
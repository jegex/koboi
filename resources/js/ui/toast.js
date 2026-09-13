const toasts = []

export function toast(message, options = {}) {
  const type = options.type || 'info'
  const text = message

  const container = document.createElement('div')
  container.dataset.testid = `nova-toast-${type}`

  const body = document.createElement('div')
  body.textContent = text
  container.appendChild(body)

  const close = () => {
    container.remove()
    const index = toasts.indexOf(container)
    if (index !== -1) {
      toasts.splice(index, 1)
    }
  }

  const actionText = options.action?.text
  if (actionText) {
    const action = document.createElement('button')
    action.textContent = actionText
    action.addEventListener('click', () => {
      close()
      options.action.onClick()
    })
    container.appendChild(action)
  }

  document.body.appendChild(container)

  if (options.duration !== null) {
    const duration = options.duration ?? 6000
    setTimeout(close, duration)
  }

  return {
    close,
    el: container,
    text,
    type,
  }
}

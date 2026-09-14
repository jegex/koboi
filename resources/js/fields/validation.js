/* validation.js — Nova frontend FormValidation: per-field validation rules +
   error lookup from an errors bag keyed by `field.attribute`. */
export function errorsForField(errors, attribute) {
  if (!errors) return undefined
  if (typeof errors === "object" && attribute in errors) return errors[attribute]
  if (typeof errors !== "string") return undefined
  const flat = new URLSearchParams(errors)
  return flat.get(attribute) ?? undefined
}

export function validateRequired(value, message = "This field is required.") {
  if (value === null || value === undefined || value === "" || (Array.isArray(value) && value.length === 0)) {
    return Array.isArray(value) ? [message] : message
  }
  return null
}

export function validateString(value, { min = 0, max = 255 } = {}) {
  if (value === null || value === undefined) return null
  const len = String(value).length
  if (len < min || len > max) return `Must be between ${min} and ${max} characters.`
  return null
}

export function validateNumber(value, { min = null, max = null } = {}) {
  if (value === null || value === undefined || value === "") return null
  const n = Number(value)
  if (!Number.isFinite(n)) return "Must be a number."
  if (min !== null && n < min) return `Must be at least ${min}.`
  if (max !== null && n > max) return `Must be at most ${max}.`
  return null
}

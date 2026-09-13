/**
 * Parse a CSS color value into a normalized RGB representation.
 *
 * @typedef {{mode: 'rgb', color: number[], alpha: number}} ParsedColor
 */

const hexPattern = /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/

const fnPattern = /^(rgba?|hsla?)\(\s*([^)]*)\)$/

export function parseColor(value) {
  if (typeof value !== 'string') {
    return undefined
  }

  const trimmed = value.trim()

  const hex = trimmed.match(hexPattern)
  if (hex) {
    return parseHex(hex[1])
  }

  const fn = trimmed.match(fnPattern)
  if (fn) {
    return parseFunction(fn[1], fn[2])
  }

  return undefined
}

/**
 * @param {string} hex
 * @returns {ParsedColor|undefined}
 */
function parseHex(hex) {
  if (hex.length === 3 || hex.length === 4) {
    const [r, g, b, a] = hex.split('').map(c => c + c)
    return {
      mode: 'rgb',
      color: [hexToByte(r), hexToByte(g), hexToByte(b)],
      alpha: a ? hexByteToFraction(a) : 1,
    }
  }

  return {
    mode: 'rgb',
    color: [
      hexToByte(hex.slice(0, 2)),
      hexToByte(hex.slice(2, 4)),
      hexToByte(hex.slice(4, 6)),
    ],
    alpha: hex.length === 8 ? hexByteToFraction(hex.slice(6, 8)) : 1,
  }
}

/**
 * @param {string} name
 * @param {string} args
 * @returns {ParsedColor|undefined}
 */
function parseFunction(name, args) {
  const hasComma = args.includes(',')
  const parts = args
    .trim()
    .split(hasComma ? /[\s,]+/ : /[\s/]+/)
    .filter(Boolean)

  if (name.startsWith('rgb')) {
    if (parts.length < 3) {
      return undefined
    }

    const [r, g, b] = parts

    if ([r, g, b].some(isInvalidRgbChannel)) {
      return undefined
    }

    return {
      mode: 'rgb',
      color: [cssNumberToByte(r), cssNumberToByte(g), cssNumberToByte(b)],
      alpha: parseAlpha(parts[3]),
    }
  }

  if (parts.length < 3) {
    return undefined
  }

  const [h, s, l] = parts

  if (isInvalidHue(h) || isInvalidPercentage(s) || isInvalidPercentage(l)) {
    return undefined
  }

  return {
    mode: 'rgb',
    color: hslToRgb(normalizeHue(h), percentageToFraction(s), percentageToFraction(l)),
    alpha: parseAlpha(parts[3]),
  }
}

/**
 * @param {string} h
 * @returns {number}
 */
function normalizeHue(h) {
  return Number(h.replace(/deg$/i, ''))
}

/**
 * @param {string} value
 * @returns {number}
 */
function percentageToFraction(value) {
  return Number(value.slice(0, -1)) / 100
}

/**
 * @param {string} h
 * @returns {boolean}
 */
function isInvalidHue(h) {
  const cleaned = h.replace(/deg$/i, '')
  return isNaN(cleaned)
}

/**
 * @param {string} channel
 * @returns {boolean}
 */
function isInvalidRgbChannel(channel) {
  if (channel.endsWith('%')) {
    return isInvalidPercentage(channel)
  }

  return isNaN(channel)
}

/**
 * @param {string} value
 * @returns {boolean}
 */
function isInvalidPercentage(value) {
  return !value.endsWith('%') || isNaN(value.slice(0, -1))
}

/**
 * @param {string} channel
 * @returns {number}
 */
function cssNumberToByte(channel) {
  if (channel.endsWith('%')) {
    return Math.round((Number(channel.slice(0, -1)) / 100) * 255)
  }

  return Math.min(255, Math.max(0, Math.round(Number(channel))))
}

/**
 * @param {string|undefined} alpha
 * @returns {number}
 */
function parseAlpha(alpha) {
  if (alpha === undefined) {
    return 1
  }

  if (alpha.endsWith('%')) {
    return Number(alpha.slice(0, -1)) / 100
  }

  return Math.min(1, Math.max(0, Number(alpha)))
}

/**
 * @param {string} byte
 * @returns {number}
 */
function hexToByte(byte) {
  return parseInt(byte, 16)
}

/**
 * @param {string} byte
 * @returns {number}
 */
function hexByteToFraction(byte) {
  return Math.round((hexToByte(byte) / 255) * 1000) / 1000
}

/**
 * Convert HSL to RGB.
 *
 * @param {number} h
 * @param {number} s
 * @param {number} l
 * @returns {number[]}
 */
function hslToRgb(h, s, l) {
  const normalizedH = ((h % 360) + 360) % 360

  const c = (1 - Math.abs(2 * l - 1)) * s
  const x = c * (1 - Math.abs(((normalizedH / 60) % 2) - 1))
  const m = l - c / 2

  let [r, g, b] = [0, 0, 0]

  if (normalizedH < 60) {
    ;[r, g, b] = [c, x, 0]
  } else if (normalizedH < 120) {
    ;[r, g, b] = [x, c, 0]
  } else if (normalizedH < 180) {
    ;[r, g, b] = [0, c, x]
  } else if (normalizedH < 240) {
    ;[r, g, b] = [0, x, c]
  } else if (normalizedH < 300) {
    ;[r, g, b] = [x, 0, c]
  } else {
    ;[r, g, b] = [c, 0, x]
  }

  return [
    Math.round((r + m) * 255),
    Math.round((g + m) * 255),
    Math.round((b + m) * 255),
  ]
}

/* Nova field formatters — DisplayValue / HelpText / Format, mirroring the
   `formatters` Nova util. */
export function formatWith(value, formatter, params) {
  if (!formatter) return value
  if (typeof formatter === 'function') return formatter(value, params)
  const formatters = {
    percent: (v) => `${v}%`,
    money: (v) => new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(v),
    date: (v) => (v ? new Date(v).toLocaleDateString() : v),
    dateTime: (v) => (v ? new Date(v).toLocaleString() : v),
    boolean: (v) => (v ? 'Yes' : 'No'),
  }
  return formatters[formatter]?.(value) ?? value
}

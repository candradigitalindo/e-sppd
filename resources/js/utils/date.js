/**
 * Format date to Indonesian locale
 * @returns {string} e.g. "25 Januari 2025"
 */
export function formatDate(value, options = {}) {
  if (!value) return '-'
  const date = new Date(value)
  if (isNaN(date)) return '-'

  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
    ...options,
  })
}

/**
 * Format datetime
 * @returns {string} e.g. "25 Jan 2025, 14:30"
 */
export function formatDateTime(value) {
  if (!value) return '-'
  const date = new Date(value)
  if (isNaN(date)) return '-'

  return date.toLocaleString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

/**
 * Format as input[type=date] value
 * @returns {string} "YYYY-MM-DD"
 */
export function toInputDate(value) {
  if (!value) return ''
  return new Date(value).toISOString().substring(0, 10)
}

/**
 * Calculate number of days between two dates (inclusive)
 */
export function daysBetween(start, end) {
  if (!start || !end) return 0
  const s = new Date(start)
  const e = new Date(end)
  return Math.max(0, Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1)
}

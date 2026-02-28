/**
 * Format number to Indonesian Rupiah
 */
export function formatRupiah(value, withSymbol = true) {
  if (value === null || value === undefined || value === '') return '-'

  const num = Number(value)
  if (isNaN(num)) return '-'

  const formatted = new Intl.NumberFormat('id-ID', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(Math.abs(num))

  const sign = num < 0 ? '-' : ''
  return withSymbol ? `${sign}Rp ${formatted}` : `${sign}${formatted}`
}

/**
 * Parse rupiah string to number
 */
export function parseRupiah(value) {
  if (!value) return 0
  return Number(String(value).replace(/[^0-9,-]/g, '').replace(',', '.')) || 0
}

/**
 * Format compact number (e.g. 1.5 Jt, 2.3 M)
 */
export function formatCompact(value) {
  const num = Number(value)
  if (isNaN(num)) return '-'
  if (num >= 1_000_000_000) return `Rp ${(num / 1_000_000_000).toFixed(1)} M`
  if (num >= 1_000_000) return `Rp ${(num / 1_000_000).toFixed(1)} Jt`
  if (num >= 1_000) return `Rp ${(num / 1_000).toFixed(0)} Rb`
  return formatRupiah(num)
}

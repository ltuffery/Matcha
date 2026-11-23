export function formatLastSeen(
  input: string,
): string {
  const date = new Date(input)
  const now = new Date()

  const diffSeconds = Math.max(0, Math.floor((+now - date.getTime()) / 1000))

  const rtf = new Intl.RelativeTimeFormat('fr', { numeric: 'auto' })

  const minutes = Math.floor(diffSeconds / 60)
  const hours = Math.floor(minutes / 60)
  const days = Math.floor(hours / 24)
  const months = Math.floor(days / 30)

  let relative: string
  if (minutes < 60) relative = rtf.format(-minutes, 'minute')
  else if (hours < 24) relative = rtf.format(-hours, 'hour')
  else if (days < 30) relative = rtf.format(-days, 'day')
  else if (months < 12) relative = rtf.format(-months, 'month')
  else return "Vu il y a plus d'un an"

  return `Vu ${relative}`
}
